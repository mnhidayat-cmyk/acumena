<!-- Header -->
<div class="container px-6 mx-auto">
  <div class="flex items-center justify-between my-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Subscription</h2>
    </div>
  </div>
</div>

<div class="container mx-auto px-6">

    <!-- Subscription Card -->
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

    <!-- Quota Usage Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        
        <!-- Projects Quota -->
        <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200">Projects</h3>
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Usage</span>
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-200">
                        <?= $project_count ?> of <?= $plan['max_projects'] ?>
                    </span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <?php 
                        $percentage = $plan['max_projects'] > 0 ? ($project_count / $plan['max_projects']) * 100 : 0;
                        $color = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                    ?>
                    <div class="<?= $color ?> h-2 rounded-full transition-all duration-300" style="width: <?= min($percentage, 100) ?>%"></div>
                </div>
            </div>
            
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <?php if($percentage >= 100): ?>
                    <span class="text-red-500 font-semibold">⚠️ Project limit reached</span>
                <?php elseif($percentage >= 80): ?>
                    <span class="text-yellow-500">⚡ Upgrade soon to create more projects</span>
                <?php else: ?>
                    <span class="text-green-500">✓ You can create <?= $plan['max_projects'] - $project_count ?> more project<?= $plan['max_projects'] - $project_count !== 1 ? 's' : '' ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- AI Generation Quota -->
        <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200">AI Generations</h3>
                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Usage</span>
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-200">
                        <?php if($plan['max_ai_generation'] == 0): ?>
                            <span class="text-green-500">Unlimited</span>
                        <?php else: ?>
                            <?= $quota_info['used'] ?> of <?= $quota_info['limit'] ?>
                        <?php endif; ?>
                    </span>
                </div>
                
                <?php if($plan['max_ai_generation'] > 0): ?>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <?php 
                            $ai_percentage = ($quota_info['used'] / $quota_info['limit']) * 100;
                            $ai_color = $ai_percentage >= 90 ? 'bg-red-500' : ($ai_percentage >= 70 ? 'bg-yellow-500' : 'bg-purple-500');
                        ?>
                        <div class="<?= $ai_color ?> h-2 rounded-full transition-all duration-300" style="width: <?= min($ai_percentage, 100) ?>%"></div>
                    </div>
                <?php else: ?>
                    <div class="w-full bg-green-200 dark:bg-green-900 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <?php if($plan['max_ai_generation'] == 0): ?>
                    <span class="text-green-500">✓ Unlimited generations available</span>
                <?php elseif($quota_info['remaining'] <= 0): ?>
                    <span class="text-red-500 font-semibold">⚠️ Monthly quota exhausted</span><br>
                    <span class="text-xs">Resets on <?= $quota_info['reset_date'] ?></span>
                <?php elseif($quota_info['remaining'] <= 3): ?>
                    <span class="text-yellow-500">⚡ Only <?= $quota_info['remaining'] ?> generation<?= $quota_info['remaining'] !== 1 ? 's' : '' ?> remaining</span><br>
                    <span class="text-xs">Resets on <?= $quota_info['reset_date'] ?></span>
                <?php else: ?>
                    <span class="text-green-500">✓ <?= $quota_info['remaining'] ?> generation<?= $quota_info['remaining'] !== 1 ? 's' : '' ?> remaining this month</span><br>
                    <span class="text-xs">Resets on <?= $quota_info['reset_date'] ?></span>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Plan Features Card -->
    <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-md mx-auto mt-8 p-6">
        <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200 mb-4">Plan Features</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Max Steps -->
            <div class="flex items-start space-x-3 pb-4 border-b dark:border-gray-700 md:border-b-0">
                <div class="flex-shrink-0 mt-1">
                    <?php 
                        $step_order = ['profile' => 1, 'swot' => 2, 'matrix-ife' => 3, 'matrix-efe' => 4, 'matrix-ie' => 5, 'matrix-ai' => 6, 'full' => 6];
                        $step_names = ['profile' => 'Profile', 'swot' => 'SWOT', 'matrix-ife' => 'Matrix IFE', 'matrix-efe' => 'Matrix EFE', 'matrix-ie' => 'Matrix IE', 'matrix-ai' => 'Strategies & Recommendations', 'full' => 'All Steps'];
                        $max_step_name = $step_names[$plan['max_step']] ?? $plan['max_step'];
                    ?>
                    <?php if($plan['max_step'] === 'full'): ?>
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    <?php else: ?>
                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Maximum Step Access</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><?= $max_step_name ?></p>
                </div>
            </div>

            <!-- Team Members -->
            <div class="flex items-start space-x-3 pb-4 border-b dark:border-gray-700 md:border-b-0">
                <svg class="flex-shrink-0 w-5 h-5 mt-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Team Members</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><?= $plan['max_team_members'] ?? 1 ?> member<?= ($plan['max_team_members'] ?? 1) !== 1 ? 's' : '' ?></p>
                </div>
            </div>

            <!-- Export Feature -->
            <div class="flex items-start space-x-3 pb-4 border-b dark:border-gray-700 md:border-b-0">
                <?php if($plan['enable_export']): ?>
                    <svg class="flex-shrink-0 w-5 h-5 mt-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                <?php else: ?>
                    <svg class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                <?php endif; ?>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Export Data</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><?= $plan['enable_export'] ? '✓ Enabled' : '✗ Not Available' ?></p>
                </div>
            </div>

            <!-- API Access -->
            <div class="flex items-start space-x-3">
                <?php if($plan['enable_api_access']): ?>
                    <svg class="flex-shrink-0 w-5 h-5 mt-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                <?php else: ?>
                    <svg class="flex-shrink-0 w-5 h-5 mt-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                <?php endif; ?>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">API Access</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><?= $plan['enable_api_access'] ? '✓ Enabled' : '✗ Not Available' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Section -->
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
