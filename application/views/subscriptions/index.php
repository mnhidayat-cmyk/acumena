<!-- Header -->
<div class="container px-6 mx-auto">
  <div class="flex items-center justify-between my-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Subscription</h2>
    </div>
  </div>
</div>

<div class="container mx-auto px-6">

    <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md mx-auto mt-8 p-6">
        <div class="flex items-top justify-between">
            <div>
                <div>
                    <div class="text-md text-gray-400 font-normal">
                        <?= $subscription->subscription_name ?>
                    </div>
                    <div class="text-4xl font-bold text-gray-700 dark:text-gray-200">
                        Rp<?= $subscription->price_monthly ?>
                        <span class="text-md text-gray-400 font-normal">/month</span>
                    </div>
                </div>
                <div>
                    <div class="text-md text-gray-400 font-normal mt-4">
                        Current billing cycle
                    </div>
                    <div class="font-bold text-lg text-gray-700 dark:text-gray-200">
                        <?= date('d M Y', strtotime($subscription->date_start)) ?> ~ <?= date('d M Y', strtotime($subscription->date_end)) ?>
                    </div>
                </div>
                
            </div>
            <div class="text-right">
                <?php if($subscription->subscription_id == 1): ?>
                <a href="<?= base_url('subscription/package') ?>" class="btn btn-soft-secondary">Upgrade to Pro</a>
                <?php else: ?>
                <a href="" class="btn btn-soft-primary">Renew Subscription</a>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md mx-auto mt-8 p-6">
        <h3 class="font-bold text-gray-700 dark:text-gray-200">Invoices</h3>
        <div class="w-full overflow-x-auto mt-4">
            <table class="w-full">
                <tbody>
                    <?php
                    foreach($invoice_history as $invoice){
                    ?>
                    <tr>
                        <td class="py-2">
                            <a href="<?= base_url('subscription/invoice/'.$invoice->uuid) ?>" class="text-blue-500 dark:text-blue-400 font-bold block">INVOICE #<?= $invoice->invoice_number ?></a>
                            <span class="text-sm text-gray-400 font-normal block"><?= date('d M Y', strtotime($invoice->date_start)) ?></span>
                        </td>
                        <td class="text-center text-gray-600 dark:text-gray-200">
                            Rp<?= number_format($invoice->price, 0, ',', '.') ?>
                        </td>
                        <td class="text-right">
                            <?php
                            if($invoice->status == 'paid'){
                                echo '<span class="text-xs bg-success px-4 py-1 rounded-full font-semibold text-white italic">Paid</span>';
                            }elseif($invoice->status == 'unpaid'){
                                echo '<span class="text-xs bg-danger px-4 py-1 rounded-full font-semibold text-white italic">Unpaid</span>';
                            }elseif($invoice->status == 'processing'){
                                echo '<span class="text-xs bg-warning px-4 py-1 rounded-full font-semibold text-white italic">Processing</span>';
                            }elseif($invoice->status == 'rejected'){
                                echo '<span class="text-xs bg-danger px-4 py-1 rounded-full font-semibold text-white italic">Rejected</span>';
                            }else{
                                echo '<span class="text-xs bg-gray-300 px-4 py-1 rounded-full font-semibold text-white italic">Canceled</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
    </div>


</div>
