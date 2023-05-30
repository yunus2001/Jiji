<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Buyer;

class OrderController extends Controller
{


            /**
     * @OA\Get(
     *     path="/api/items/{id}/orders/{buyer}",
     *     tags={"Orders"},
     *     summary="select interested buyer",
     *     description="As a Seller, I want to select an interested Buyer and mark an item as “Sold” so that the Item will no longer be listed on the home page.",
     *     operationId="selectInterestedBuyer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Item id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="buyer",
     *         in="path",
     *         description="Buyer id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found"
     *     ),
     *     security={{"bearer_token":{}}},
     * )
     *
     * @param int $id
     */

    // select interested buyer
    public function selectInterestedBuyer(Item $item, Buyer $buyer)
    {
        
        Item::where('id', $item->id)
            ->update(['is_sold' => true]);

        return response(['buyer' =>  $buyer]);
    }



/**
     *
     *  @OA\Post(
     *   path="/api/items/{id}/order",
     *   tags={"Orders"},
     *   security={{"bearer_token":{}}},
     *   summary="Order an item",
     *   description="As a Buyer, I want to perform an action to show my interest for an Item and enter my name, email and location so that the Seller can see my information and contact me later.",
     *   operationId="orderStore",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Item to make Order",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={ "quantity", "country", "street", "state", "firstName", "email", "lastName", "local_government" },
     *               @OA\Property(property="firstName", type="text"),
     *               @OA\Property(property="lastName", type="text"),
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="state", type="street"),
     *               @OA\Property(property="street", type="street"),
     *               @OA\Property(property="local_government", type="text"),
     *               @OA\Property(property="country", type="text"),
     *               @OA\Property(property="quantity", type="text"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * )
     */

    public function orderStore(Request $request, Item $item)
    {
        $this->validate($request, [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => ['required', 'string', 'email'],
            'country' => 'required',
            'state' => 'required',
            'local_government' => 'required',
            'street' => 'required',
            'quantity' => 'required',

        ]);

        $buyer = Buyer::create([

            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email
    
        ]) ;
       
    
        $address = $buyer->address()->create([
    
            'user_id' => $buyer->id,
            'country' => $request->country,
            'state' => $request->state,
            'street' => $request->street,
            'local_government' => $request->local_government
        ]);
    
       $order = $buyer->order()->create([
            'seller_id' => $item->user_id,
            'item_id' => $item->id,
            'quantity' => $request->quantity,
        ]);
    
        //you can't play order on your items
        if($item->user_id === $order['buyer_id'] ){
            
            return response(['message' => 'You cannot order for your item']);
        }
    
        if($item->item_quantity < $order['quantity']){
    
            return response([
                'message' => $item->item_quantity > 0 ? 'we only have '.$item->item_quantity. ' '.$item->name  : 'Out of stock' 
             ]);
        }
    
        Item::where('id', $item->id)->update([
                    'item_quantity' => $item->item_quantity - $order['quantity']
                ]);
    
        return  response([
                'order' => $order,
                'buyer' => $buyer,
                'address' => $address,
       ]);
       
    

    }
}