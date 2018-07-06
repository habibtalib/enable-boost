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

class ProcessBoostCollection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $api_key = '';
    protected $ii = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $api_key, $ii = 1)
    {
        $this->api_key = $api_key;
        $this->ii = $ii;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $connnect = (new Connect($this->api_key))->detectMode();
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        $billplz = new API($connnect);

       // Log::debug('This is collection only and number '. $this->ii);

        /* This is for Collection */

        $ii = $this->ii;
            $response = $billplz->toArray($billplz->getCollectionIndex(array('page'=>strval($ii), 'status'=>'')));

        if (empty($response[1]['collections'])) {
            return;
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

        ProcessBoostCollection::dispatch($this->api_key, ++$ii);
    }
}
