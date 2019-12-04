<?php $__env->startSection('title', 'Owner Billing Profile'); ?>
<?php $__env->startSection('header'); ?>
    <page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<?php if($message = session('status')): ?>
    <owner-billing v-bind:message="<?php echo e(json_encode(['status' => 'success', 'body' => [$message]])); ?>" v-bind:user-info="<?php echo e($userInfo); ?>" v-bind:user-name="<?php echo e($userName); ?>" v-bind:owner-id="<?php echo e($ownerId); ?>"></owner-billing>
    <?php elseif($errors->has('merchant_type')): ?>
    <owner-billing v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => ['Choose payment method']])); ?>" v-bind:user-info="<?php echo e($userInfo); ?>" v-bind:user-name="<?php echo e($userName); ?>" v-bind:owner-id="<?php echo e($ownerId); ?>"></owner-billing>
    <?php elseif($errors->any()): ?>
    <owner-billing v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>" v-bind:user-name="<?php echo e($userName); ?>" v-bind:user-info="<?php echo e($userInfo); ?>" v-bind:old-input="<?php echo e(json_encode(['merchant_type' => old('merchant_type', null)])); ?>" v-bind:owner-id="<?php echo e($ownerId); ?>"></owner-billing>
    <?php else: ?>
    <owner-billing v-bind:user-info="<?php echo e($userInfo); ?>" v-bind:user-name="<?php echo e($userName); ?>" v-bind:owner-id="<?php echo e($ownerId); ?>"></owner-billing>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>