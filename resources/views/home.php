<h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl black dark:text-white text-stone-800">
    Home Page</h1>
<form action="/" method="post">
    <?= request()->getHttpMethod() ?>
    <input type="hidden" name="_method" id="_method" value="delete">
    <button>su</button>
</form>