<?php $__env->startSection('title', 'Owner Booking'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<?php if($errors->any()): ?>
    <owner-booking v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>" v-bind:bid-info="<?php echo e($bidInfo); ?>" v-bind:old-input="<?php echo e(json_encode(['merchant_type' => old('merchant_type', null)])); ?>"></owner-booking>
    <?php else: ?>
    <owner-booking v-bind:bid-info="<?php echo e($bidInfo); ?>"></owner-booking>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>