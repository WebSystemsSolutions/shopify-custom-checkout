<?php

namespace App\Http\Controllers\Shopify;

use App\Dto\Shopify\AddShipmentMethodData;
use App\Dto\Shopify\ConfirmCheckoutDto;
use App\Dto\Shopify\ShipmentsDto;
use App\Http\Controllers\Controller;
use App\Repository\Shopify\Session\CartRepository;
use App\Services\Shopify\CartService;
use App\Services\Shopify\OrderService;
use App\Validator\Shopify\AddShipmentMethodValidator;
use App\Validator\Shopify\ConfirmCheckoutValidator;
use App\Validator\Shopify\ShipmentValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kris\LaravelFormBuilder\FormBuilder;

class CheckoutController extends Controller
{
    private CartRepository $cartRepository;

    private CartService $cartService;

    private OrderService $orderService;

    public function __construct(
        CartRepository $cartRepository,
        CartService $cartService,
        OrderService $orderService
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function checkout(Request $request)
    {
        $cartItems = json_decode($request->get('cart'));

        if (is_null($cartItems)) {
            return view('shopify.empty-checkout');
        }

        $checkoutItems = $this->cartService->getCheckoutItems($cartItems);

        $cartItemsPrice = $this->cartService->calculateCart($checkoutItems);
        $this->cartRepository->putItems($checkoutItems);

        $shippingZonesNames = $this->cartService->getShippingZoneNames();

        $shippingZonesMethods = [];

        return view('shopify.checkout', [
            'shippingZonesNames' => $shippingZonesNames,
            'cartItemsPrice' => $cartItemsPrice,
            'checkoutItems' => $checkoutItems,
            'shippingZonesMethods' => $shippingZonesMethods,
        ]);
    }

    public function confirm(Request $request, ConfirmCheckoutValidator $validator)
    {
        $dto = ConfirmCheckoutDto::createFromData($validator->validate($request));

        $this->orderService->createOrder($dto);

        return redirect('checkout');
    }

    public function getShipments(Request $request, ShipmentValidator $validator)
    {
        $dto = ShipmentsDto::createFromData($validator->validate($request));

        $shippingZonesMethods = $this->cartService->getShippingZoneMethods($dto->country);

        $this->cartRepository->flushShippingMethod();

        $cart = $this->cartRepository->getCart();

        $priceList = $this->cartService->calculateCartWithShippingMethod($cart);

        return json_encode(
            [
                'methods' => $shippingZonesMethods,
                'priceList' => $priceList
            ]
        );
    }

    public function addShipmentMethod(Request $request, AddShipmentMethodValidator $validator)
    {
        $dto = AddShipmentMethodData::createFromData($validator->validate($request));

        $this->cartRepository->putShippingMethod(['title' => $dto->title, 'price' => $dto->price]);

        $cart = $this->cartRepository->getCart();

        $priceList = $this->cartService->calculateCartWithShippingMethod($cart);

        return json_encode($priceList);
    }
}
