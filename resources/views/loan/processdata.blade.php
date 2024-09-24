<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Process Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                   <button id="id_processbutton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg sm:rounded-lg">
                    Process Data
                  </button>
                  {{-- table --}}
                  <div class="flex flex-col">
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                      <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden" id="tableContainer">
                         
                        </div>
                      </div>
                    </div>
                  </div>
                   {{-- table end --}}
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#id_processbutton').click(function (e) {
                e.preventDefault();
                $.ajax({
                        url: '/process-data',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            alert(response.message)
                            if (response.data && response.data.length > 0) {
                                let table = $('<table></table>').addClass('min-w-full text-left text-sm font-light text-surface dark:text-white');
                                let headerRow = $('<tr></tr>');
                                Object.keys(response.data[0]).forEach(function (key) {
                                    headerRow.append($('<th></th>').text(key).addClass('px-4 py-2'));
                                });
                                table.append(headerRow);
                                response.data.forEach(function (item) {
                                    let row = $('<tr></tr>');
                                    Object.values(item).forEach(function (value) {
                                        row.append($('<td></td>').text(value).addClass('border px-4 py-2'));
                                    });
                                    table.append(row);
                                });
                                $('#tableContainer').html(table);
                            } else {
                                $('#tableContainer').html('<p>No data available.</p>');
                            }
                        },
                        error: function (xhr) {
                            console.error('Error:', xhr.responseText);
                        }
                    });

            });
        });
    </script>
</x-app-layout>
