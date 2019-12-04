<?php $__env->startSection('title', 'Captain Bio'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:dark="true" v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <captain-bio v-bind:hire="<?php echo e($hire); ?>" v-bind:captain-info="<?php echo e($captainInfo); ?>"></captain-bio>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>