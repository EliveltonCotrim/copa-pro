<?php

namespace App\Http\Controllers;

use App\Enum\{PaymentStatusEnum, RegistrationPlayerStatusEnum};
use App\Http\Requests\AsaasWebHookRequest;
use App\Models\Payment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Log};

class AsaasWebhookController extends Controller
{
    public function __invoke(AsaasWebHookRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();

        try {

            if ($data['event'] === 'PAYMENT_RECEIVED') {
                $payment = Payment::where('transaction_id', $data['payment']['id'])->firstOrFail();

                $payment->update(
                    [
                        'status'                  => PaymentStatusEnum::parse($data['payment']['status']),
                        'payment_Date'            => $data['payment']['paymentDate'],
                        'confirmed_date'          => $data['payment']['confirmedDate'],
                        'transaction_receipt_url' => $data['payment']['transactionReceiptUrl'],
                    ]
                );

                $payment->registrationPlayer->update(['status' => RegistrationPlayerStatusEnum::APPROVED]);
            }

            DB::commit();

            return response('ok', JsonResponse::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            Log::error('Payment not found:' . $e->getMessage(), ['transaction_id' => $data['payment']['id']]);

            return response('Payment not found', JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Unexpected error:' . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'data' => $data]);

            return response('Internal server error', JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
