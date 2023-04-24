<?php require('partials/head.php') ?>

    <header class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Web Portal Task</h1>
        </div>
    </header>
    <main>
        <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
            <div class="flex justify-between mb-3">
                <form id="search-form">
                    <div class="flex items-center gap-x-3">
                        <input
                            type="text"
                            id="search-box"
                            class="w-52 block rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600"
                        >
                        <button type="submit" id="search-button" class="bg-blue-600 text-white py-1.5 px-4 rounded-md">
                            Search
                        </button>
                    </div>
                </form>
                <button id="modal-trigger" class="bg-slate-600 text-white py-1.5 px-4 rounded-md">
                    Open Modal
                </button>
            </div>
            <table id="tasks" class="w-full table-fixed border-collapse border border-slate-400 bg-white">
                <thead>
                <tr>
                    <th class="border border-slate-300 p-2">Task</th>
                    <th class="border border-slate-300 p-2">Title</th>
                    <th class="border border-slate-300 p-2">Description</th>
                    <th class="border border-slate-300 p-2">Color Code</th>
                </tr>
                </thead>
                <tbody>
                <tr class="no-data">
                    <td colspan="4" class="border border-slate-300 p-2 text-center">No data.</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="modal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <img class="image hidden mb-3 object-cover h-48 w-96"/>
                            <button id="image-trigger" class="bg-slate-600 text-white py-1.5 px-4 rounded-md">
                                Select Image
                            </button>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                onclick="toggleModal()"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>

        let dataset = []

        function view(data = []) {
            const table = document.querySelector('#tasks tbody')
            if (Array.isArray(data) && data.length) {
                let content = ''
                data.forEach((item) => {
                    content = content.concat(`
                        <tr>
                            <td class="border border-slate-300 p-2">${item.task}</td>
                            <td class="border border-slate-300 p-2">${item.title}</td>
                            <td class="border border-slate-300 p-2">${item.description}</td>
                            <td class="border border-slate-300 p-2">
                                <div class="w-32 px-2 py-1 rounded-md text-center bg-[${item.colorCode}]">
                                    ${item.colorCode}
                                </div>
                            </td>
                        </tr>
                    `)
                })
                table.innerHTML = content
            } else {
                table.innerHTML = `
                    <tr class="no-data">
                        <td colspan="4" class="border border-slate-300 p-2 text-center">No data.</td>
                    </tr>
                `
            }
        }

        async function load() {
            const response = await fetch('/tasks');
            if (response.ok) {
                const json = await response.json();
                if (Array.isArray(json)) {
                    dataset = json
                    view(dataset)
                }
            }
        }

        function reload() {
            setInterval(() => {
                load()
            }, 60 * 60 * 1000)
        }

        function search() {
            const input = document.getElementById('search-box')
            const keyword = input.value.toLowerCase()
            if (keyword.length) {
                let data = dataset.filter((item) => {
                    return item.task.toLowerCase().indexOf(keyword) !== -1 ||
                        item.description.toLowerCase().indexOf(keyword) !== -1 ||
                        item.title.toLowerCase().indexOf(keyword) !== -1 ||
                        item.colorCode.toLowerCase().indexOf(keyword) !== -1
                })
                view(data)
            } else {
                view(dataset)
            }
        }

        function toggleModal() {
            const modal = document.getElementById('modal')
            modal.classList.toggle('hidden')
        }

        const searchForm = document.getElementById('search-form')
        searchForm.addEventListener("submit", (event) => {
            event.preventDefault()
            search()
        })

        const modalTrigger = document.getElementById('modal-trigger')
        modalTrigger.addEventListener('click', toggleModal)

        const imageTrigger = document.getElementById('image-trigger')
        imageTrigger.addEventListener('click', () => {
            const fileInput = document.createElement("input")
            fileInput.type = "file"
            fileInput.click()
            fileInput.addEventListener("change", (event) => {
                const file = event.target.files[0]
                const image = document.querySelector('.image')
                image.src = URL.createObjectURL(file)
                image.classList.remove('hidden')
            });
        })

        load()
        reload()

    </script>

<?php require('partials/footer.php') ?>