<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

use Billplz\Connect;
use Billplz\API;

class ProcessBoost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $api_key = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $connnect = (new Connect($this->api_key))->detectMode();
        $billplz = new API($connnect);

        for ($ii=1;; $ii++) :
            Log::debug('hi ini ialah perjalanan yang ke-'.strval($ii));
            $response = $billplz->toArray($billplz->getCollectionIndex(array('page'=>strval($ii), 'status'=>'')));

            if (empty($response[1]['collections'])) {
                break;
            }

            $collection_id = [];

            foreach ($response[1]['collections'] as $col) {
                $collection_id[] = $col['id'];
            }

            $response = $billplz->getPaymentMethodIndex($collection_id);

            $active_payment_method = [];

            foreach ($response as $resp) {
                $single_active_payment_method = [];
                $single_payment_method = $billplz->toArray($resp);
                foreach ($single_payment_method[1]['payment_methods'] as $all) {
                    if ($all['active']) {
                        $single_active_payment_method[] = $all['code'];
                    }
                }
                if (!in_array('boost', $single_active_payment_method)) {
                //Add Boost here to activate
                    $single_active_payment_method[] = 'boost';
                }
                $active_payment_method[] = $single_active_payment_method;
            }

            $count_collection = 0;
            foreach ($collection_id as $col) {
                $parameter = array(
                'collection_id' => $col
                );
                for ($i=0; $i<sizeof($active_payment_method[$count_collection]); $i++) {
                    $parameter['payment_methods'][] = ['payment_methods[][code]' => $active_payment_method[$count_collection][$i]];
                }
                $billplz->updatePaymentMethod($parameter);
            }
        endfor;
    }
}
