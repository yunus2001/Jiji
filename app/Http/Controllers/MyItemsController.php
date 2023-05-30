<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;

class MyItemsController extends Controller
{   
     /**
         * @OA\Get(
         *     path="/api/myItems",
         *     tags={"Items"},
         *     security={{"bearer_token":{}}},
         *     summary="Seller Items",
         *     description="As a Seller, I want to see my items and their descriptions in a list so that I can view them all on the same page.",
         *     operationId="myIndex",
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
        
        public function myIndex(Request $request)
        {
            
            $myItems = Item::with('address')
                            ->where('user_id', $request->user()->id)
                            ->get();
            return $myItems;
        }
        
        /**
        * @OA\Get(
        *     path="/api/myItems/{id}/orders",
        *     tags={"Items"},
        *     summary="show order on myItem",
        *     description="Returns a single Item",
        *     operationId="showMyItem",
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
        
        // show order on myItems
        public function showMyItem($id)
        {
        // $user_id = $item->user_id;
        $item =  Item::find($id);
        
        if(!$item){
        
            return response(['message' => 'item ' .$id. ' not found']);
         }
        
        $orders = Order::with('address')
                        ->where('seller_id', $item->user_id)
                        ->get();
        return $orders;
        }
        /**
     * @OA\Delete(
     *     path="/api/myItems/{id}",
     *     tags={"Items"},
     *     security={{"bearer_token":{}}},
     *     summary="Delete a single Item  from myItems",
     *     description="Delete a single Item",
     *     operationId="destroyMyItem",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of Item to return",
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

    // delete myItem
    public function destroyMyItem(Item $item)
    {
                
        $item->delete();
        return response(['message' => $item->name . ' deleted succesfully']);
    }

}
