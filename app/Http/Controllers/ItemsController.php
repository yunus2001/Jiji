<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;


class ItemsController extends Controller
{
    
     /**
         * @OA\Get(
         *     path="/api/items",
         *     tags={"Items"},
         *     security={{"bearer_token":{}}},
         *     summary="Get all Items posted by a seller",
         *     description="As a Buyer, I want to view all items on the home page with price, description, image and the location of the Seller so that I can perform an action to show my interest.",
         *     operationId="index",
         *     @OA\Parameter(
         *         name="status",
         *         in="query",
         *         description="Status values that needed to be considered for filter",
         *         required=true,
         *         explode=true,
         *         @OA\Schema(
         *             default="available",
         *             type="string",
         *             enum={"available", "pending", "sold"},
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="successful operation",
         *        
         *     ),
         *     @OA\Response(
         *         response=400,
         *         description="Invalid status value"
         *     )
         *    
         * )
         */
    //index
    public function index()
    {
    
        // return Item::with('address')
        return Item::with('address:id,addresable_id,state,street,local_government')
                    ->where('is_sold', false)
                    ->where('item_quantity', '>',  0)
                    ->get();

    }

   

    
            /**
     * @OA\Get(
     *     path="/api/items/{id}/orders",
     *     tags={"Items"},
     *     summary="show order on item",
     *     description="As a Seller, I want to view each Item I added and see the details of the Item along with Buyers that are interested in the item so that I can contact the ",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Item to return",
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

    // show interested buyers
    public function show(Item $item)
    {

        $orders = Order::with('buyer.latestAddress')
                        ->where('seller_id', $item->user_id)
                        ->get();
        return $orders;

    }

        /**
     * Add a new Item to the store.
     *
     *  @OA\Post(
     ** path="/api/items",
     *   tags={"Items"},
     *   security={{"bearer_token":{}}},
     *   summary=" create Items",
     *   description="As a Seller, I want to create a new Item with price, description, and one image so that I can put it up for sale.",
     *   operationId="store",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name", "price", "description", "quantity", "image" },
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="price", type="text"),
     *               @OA\Property(property="description", type="text"),
     *               @OA\Property(property="quantity", type="text"),
     *               @OA\Property(property="image", type="file"),
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

     public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'image' => 'required',
            'description' => 'required',
            'quantity' => 'required'
           
        ]);


        
        $item = [
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'item_quantity' => $request->quantity,
            'image' => asset('/storage/'.$request->file('image')
                                                 ->store('images', 'public'))

        ];
        
        return Item::create($item);
    }

     /**
     * @OA\Delete(
     *     path="/api/items/{id}",
     *     tags={"Items"},
     *     security={{"bearer_token":{}}},
     *     summary="Delete a single Item",
     *     description="As a Seller, I want to delete Items that I choose so that I can remove them from my list of Items.",
     *     operationId="destroy",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Item to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *   
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplier"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Item not found"
     *     ),
     *    
     * )
     *
     * @param int $id
     */

    public function destroy(Item $item)
    {

        $item->delete();
        return response([
            'message' => $item->name. ' deleted'
        ]);
    }
}
