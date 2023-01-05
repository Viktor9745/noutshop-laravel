<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function cart(Item $item){
        $itemCart=null;
        if(Auth::check()){
            $itemCart = Auth::user()->itemsCart()->where('in_cart', 'in_cart')->get();
        }

        return view('items.cart',['itemCart'=>$itemCart, 'item'=>$item]);
    }



    public function addcart(Request $request, Item $item){
        $request->validate([
            'quantity' => 'required',
            'ram'=>'required',
            'memory'=>'required',
        ]);

        $itemCart = Auth::user()->itemsCart()->where('item_id', $item->id)->first();
        if($itemCart!=null){
        Auth::user()->itemsCart()->updateExistingPivot($item->id, ['quantity'=>$request->input('quantity'),'ram'=>$request->input('ram'),'memory'=>$request->input('memory')]);
        return back()->with('message', __('messages.cart_update'));
        }else{
            Auth::user()->itemsCart()->attach($item->id, ['quantity'=>$request->input('quantity'),'ram'=>$request->input('ram'),'memory'=>$request->input('memory')]);
            return back()->with('message', __('messages.item_add_cart'));
        }
    }

    public function uncart(Item $item){
        $itemCart = Auth::user()->itemsCart()->where('item_id', $item->id)->first();
        if($itemCart != null){
            Auth::user()->itemsCart()->detach($item->id);
            $item->usersCart()->detach();
        }
        return back()->with('message', __('messages.item_delete_cart'));
    }

    public function itemsByCategory(Category $category)
    {
        $items = $category->items;
        return view('items.index', ['allItems' => $items, 'categories' => Category::all(), 'manufacturers' => Manufacturer::all()]);
    }
    public function itemsByManufacturer(Manufacturer $manufacturer)
    {
        $items = $manufacturer->items;
        return view('items.index', ['allItems' => $items, 'manufacturers' => Manufacturer::all(), 'categories' => Category::all()]);
    }
    public function index()
    {
        $allItems = Item::all();
        return view('items.index',['allItems' => $allItems, 'categories' => Category::all(),'manufacturers' => Manufacturer::all()]);
    }

    public function create()
    {
        $this->authorize('create', Item::class);
        return view('items.create',['categories'=> Category::all(), 'manufacturers' => Manufacturer::all()]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Item::class);
        $request->validate([
            'name' => 'required|max:255',
            'price'=>'required',
            'ram' =>'required',
            'memory' =>'required',
            'cpu'=>'required',
            'gpu'=>'required',
            'manufacturer_id'=>'required|numeric|exists:manufacturers,id',
            'image'=>'required|image|mimes:jpg,png,jpeg,svg|max:2048',
            'category_id'=>'required|numeric|exists:categories,id',
        ]);

        $input = $request->all();

        if($image = $request->file('image')){
            $destinationPath='images/';
            $itemImage=date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath,$itemImage);
            $input['image']="$itemImage";
        }

//         item::create($input+['user_id'=>Auth::user()->id]);
        Auth::user()->items()->create($input);
        return redirect()->route('items.index')->with('message', __('messages.item_save'));
    }

    public function show(Item $item)
    {
        $comments = $item->comments;
        if(Auth::check()){
            $itemCart = Auth::user()->itemsCart()->where('item_id', $item->id)->first();
        }
        if(isset($itemCart))
            return view('items.show', ['item'=>$item,'categories'=> Category::all(), 'manufacturers' => Manufacturer::all(), 'itemCart'=>$itemCart]);
        else
            return view('items.show', ['item'=>$item,'categories'=> Category::all(), 'manufacturers' => Manufacturer::all()]);

    }

    public function edit(Item $item)
    {
        $this->authorize('update', $item);
        return view('items.edit',['item'=>$item,'categories'=> Category::all(), 'manufacturers' => Manufacturer::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        // $item->update([
        //     'name'=>$request->input('name'),
        //     'price'=>$request->input('price'),
        //     'ram'=>$request->input('ram'),
        //     'memory'=>$request->input('memory'),
        //     'cpu'=>$request->input('cpu'),
        //     'gpu'=>$request->input('gpu'),
        //     'manufacturer_id' => $request->manufacturer_id,
        //     'category_id' => $request->category_id,

        // ]);
        $request->validate([
            'name'=>'required',
            'price'=>'required',
            'ram' =>'required',
            'memory' =>'required',
            'cpu'=>'required',
            'gpu'=>'required',
            'manufacturer_id'=>'required|numeric|exists:manufacturers,id',
            'category_id'=>'required|numeric|exists:categories,id',
        ]);

        $input = $request->all();

        if($image = $request->file('image')){
            $destinationPath='images/';
            $itemImage=date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath,$itemImage);
            $input['image']="$itemImage";
        }else{
            unset($input['image']);
        }
        $item->update($input);

        return redirect()->route('items.index')->with('message',__('messages.item_update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);
        $item->delete();
        return redirect()->route('items.index')->with('message', __('messages.item_delete'));
    }
}
