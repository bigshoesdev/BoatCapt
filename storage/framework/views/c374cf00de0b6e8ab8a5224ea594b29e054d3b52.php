<?php $__env->startSection('title', 'Create Bid'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if($errors->any()): ?>
    <create-bid v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>" v-bind:bid-request="<?php echo e($bidRequest); ?>" v-bind:old-input="<?php echo e(json_encode(['chargeType' => old('chargeType'), 'hours' => old('hours'), 'amount' => old('amount'), 'describe' => old('describe')])); ?>"></create-bid>
    <?php else: ?>
    <create-bid v-bind:bid-request="<?php echo e($bidRequest); ?>" v-bind:old-input="<?php echo e(json_encode(['chargeType' => '', 'hours' => 1, 'amount' => '', 'describe' => ''])); ?>"></create-bid>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>