<?php $__env->startSection('title', 'Captain Leave Review'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<?php if($message = session('status')): ?>
	<captain-leave-review v-bind:message="<?php echo e(json_encode(['status' => 'success', 'body' => [$message]])); ?>" v-bind:trip-info="<?php echo e($tripInfo); ?>" v-bind:review-info="<?php echo e($reviewInfo); ?>"></captain-leave-review>
    <?php elseif($errors->any()): ?>
    <captain-leave-review v-bind:message="<?php echo e(json_encode(['status' => 'failed', 'body' => $errors->all()])); ?>" v-bind:trip-info="<?php echo e($tripInfo); ?>" v-bind:review-info="<?php echo e($reviewInfo); ?>"></captain-leave-review>
    <?php else: ?>
    <captain-leave-review v-bind:trip-info="<?php echo e($tripInfo); ?>" v-bind:review-info="<?php echo e($reviewInfo); ?>"></captain-leave-review>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>