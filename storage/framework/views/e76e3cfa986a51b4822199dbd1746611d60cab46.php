<?php $__env->startSection('title', 'Lander'); ?>
<?php $__env->startSection('header'); ?>
	<page-header v-bind:dark="true" v-bind:param="<?php echo e($param); ?>"></page-header>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <lander v-bind:info="<?php echo e($info); ?>"></lander>    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('templates.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>