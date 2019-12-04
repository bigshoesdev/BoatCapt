<?php $__env->startSection('title', 'Captain Bid Request Detail'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <bid-request-detail v-bind:bid-request="<?php echo e($bidRequest); ?>"></bid-request-detail>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>