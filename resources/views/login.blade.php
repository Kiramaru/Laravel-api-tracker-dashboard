<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/visitor-tracker.js"></script>
   
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow w-96">
        <h2 class="text-2xl mb-4">Авторизация</h2>
        
        <?php if ($errors->any()): ?>
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <?php echo csrf_field(); ?>
            <input type="email" name="email" placeholder="Email" class="w-full border p-2 mb-2 rounded" required>
            <input type="password" name="password" placeholder="Password" class="w-full border p-2 mb-4 rounded" required>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded">Войти</button>
        </form>
    </div>
</body>
</html>
