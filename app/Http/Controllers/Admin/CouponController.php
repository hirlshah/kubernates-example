<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Helper\StripeConnect;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Response;
use MetaTag;
use Session;
use Yajra\Datatables\Datatables;

class CouponController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() 
    {
        MetaTag::set('title', config('app.rankup.company_title').' - Coupons');
        MetaTag::set('description', config('app.rankup.company_title').' Coupons Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $couponCount = Coupon::count();
        return view('admin.coupon.index', compact('couponCount'));
    }

    /**
     * Get all Coupons
     */
    public function getData() 
    {
        return Datatables::of(Coupon::select('id', 'code', 'description', 'is_active'))
            ->addColumn( 'action', function ( $data ){
            return
                '<a href="javascript:;" data-url="' . url( '/admin/coupons/' . $data->id) . '" class="modal-popup-view btn control-action legitRipple"><i class="fa fa-eye"></i></a>' .
                '<a href="' . url( '/admin/coupons/' . $data->id . '/edit' ) . '" class="btn btn-edit"><i class="feather-edit"></i></a>' .
                '<a href="javascript:;" data-url="' . url( '/admin/coupons/' . $data->id ) . '" class="modal-popup-delete btn btn-delete"><i class="feather-trash-2"></i></a>';
            })
        ->rawColumns(['action'])
        ->make( true );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() 
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Add Coupon'));
        MetaTag::set('description', config('app.rankup.company_title').' Add Coupon Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        if(empty(config('services.stripe.secret'))){
            abort(403, 'Please configure stripe payment');
        }
        return view('admin.coupon.create_update');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CouponRequest $request
     *
     * @return Response
     */
    public function store(CouponRequest $request) 
    {
        $data = $request->all();
        if($request->discount_amount && $request->discount_percentage){
            Session::flash('error', __('Choose either Discount Perecentage or Discount Amount.'));
            return redirect(route('coupons.create'))->withInput();
        }
        $data['min_downline'] = isset($request->min_downline) ? 1 : 0;
        $data['is_active'] = isset($request->is_active)  ? true  : false;
        $stripeCoupon = StripeConnect::createCoupon($data['code'], $data['discount_amount'], $data['discount_percentage']);
        if($stripeCoupon['res_status'] === 'error'){
            Session::flash( 'error', $stripeCoupon['message'] );
            return redirect(route('coupons.create'))->withInput();
        } else {
            $data['stripe_id'] = $stripeCoupon['id'];
        }
        if ( Coupon::create( $data ) ) {
            Session::flash('success', __('Coupon has been added!'));
            return redirect()->back();
        } else {
            Session::flash('error', __('Unable to create coupon.'));
            return redirect(route('coupons.create'))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Coupon $coupon
     * @return Response
     */
    public function show(Coupon $coupon) 
    {
        $data  = [
            'ID'                    =>  $coupon->id,
            'Code'                  =>  $coupon->code,
            'Stripe Id'             =>  $coupon->stripe_id,
            'Discount Percentage'   =>  $coupon->discount_percentage,
            'Discount Amount'       =>  $coupon->discount_amount,
            'Description'           =>  $coupon->description,
            'expiration'            =>  $coupon->expiration,
            'min_downline'          =>  $coupon->min_downline,
            'min_usd_amount'        =>  $coupon->min_usd_amount,
            'is_active'             =>  $coupon->is_active,
        ];

        return $data;
    }

    /**
     * Display a coupon edit form.
     *
     * @param Coupon $coupon
     *
     * @return Response
     */
    public function edit(Coupon $coupon) 
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Edit Coupon'));
        MetaTag::set('description', config('app.rankup.company_title').' Edit Coupon Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        if(empty(config('services.stripe.secret'))){
            abort(403, 'Please configure stripe payment');
        }
        return view('admin.coupon.create_update',compact('coupon'));
    }

    /**
     * Update Coupon
     *
     * @param Coupon $coupon
     * @param CouponRequest $request
     *
     * @return Response
     */
    public function update(CouponRequest $request, Coupon $coupon) 
    {
        $data = $request->all();
        $data['min_downline'] = isset($request->min_downline) ? 1 : 0;
        $data['is_active'] = isset($request->is_active)  ? true  : false;
        if(floatval($data['discount_percentage']) !== floatval($coupon->discount_percentage) || floatval($data['discount_amount']) !== floatval($coupon->discount_amount)){
            $deleteStripeCoupon = StripeConnect::deleteCoupon($coupon->stripe_id);
            if($deleteStripeCoupon['res_status'] == 'error'){
                Session::flash( 'error', $deleteStripeCoupon['message'] );
                return redirect(route('coupons.edit', ['coupon'=>$coupon->id]))->withInput();
            }
            $stripeCoupon = StripeConnect::createCoupon($data['code'], $data['discount_amount'], $data['discount_percentage']);
            if($stripeCoupon['res_status'] === 'error'){
                Session::flash( 'error', $stripeCoupon['message'] );
                return redirect(route('coupons.edit', ['coupon'=>$coupon->id]))->withInput();
            } else {
                $data['stripe_id'] = $stripeCoupon['id'];
            }
        } else {
            $stripeCoupon = StripeConnect::updateCoupon($coupon->stripe_id, $data['code']);
            if($stripeCoupon['res_status'] === 'error'){
                Session::flash( 'error', $stripeCoupon['message'] );
                return redirect(route('coupons.edit', ['coupon'=>$coupon->id]))->withInput();
            } else {
                $data['stripe_id'] = $stripeCoupon['id'];
            }
        }
        if ( $coupon->update( $data ) ) {
            Session::flash('success', __('Coupon has been updated!'));
            return redirect()->back();
        } else {
            Session::flash('error', __('Unable to update coupon.'));
            return redirect(route('coupons.edit', ['coupon'=>$coupon->id]));
        }
    }

    /**
     * Delete Coupon
     *
     * @param Coupon $coupon
     *
     * @return Response
     */
    public function destroy(Coupon $coupon) 
    {
        $deleteStripeCoupon = StripeConnect::deleteCoupon($coupon->stripe_id);
        if($deleteStripeCoupon['res_status'] == 'error'){
            Session::flash( 'error', $deleteStripeCoupon['message'] );
            return redirect(route('coupons.edit', ['coupon'=>$coupon->id]))->withInput();
        }
        $coupon->delete();
    }
}