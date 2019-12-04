<?php $__env->startSection('title', 'Sign Up'); ?>
<?php $__env->startSection('header'); ?>
    <page-header v-bind:dark="true" v-bind:param="<?php echo e(json_encode(['avatar' => null, 'searchable' => true, 'login' => false])); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if($errors->any()): ?>
    <regist v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>" v-bind:param="<?php echo e(json_encode(['account_type' => old('account_type'), 'name' => old('name'), 'email' => old('email')])); ?>"></regist>
    <?php elseif(isset($account_type)): ?>
    <regist v-bind:param="<?php echo e(json_encode(['account_type' => $account_type == 'owner' ? 1 : ($account_type == 'captain' ? 0 : -1), 'name' => '', 'email' => ''])); ?>"></regist>
    <?php else: ?>
    <regist v-bind:param="<?php echo e(json_encode(['account_type' => -1, 'name' => '', 'email' => ''])); ?>"></regist>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>