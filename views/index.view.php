<?php require('partials/head.php') ?>

    <header class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Web Portal Task</h1>
        </div>
    </header>
    <main>
        <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        </div>
    </main>

    <script>

        async function loadData() {
            const response = await fetch('/tasks');
            const json = await response.json();
            console.log(json)
        }

        loadData()

    </script>

<?php require('partials/footer.php') ?>