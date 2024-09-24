<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Loan Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                   {{-- table --}}
                   <div class="flex flex-col">
                    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                      <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                          <table
                            class="min-w-full text-left text-sm font-light text-surface dark:text-white">
                            <thead
                              class="border-b border-neutral-200 font-medium dark:border-white/10">
                              <tr>
                                <th scope="col" class="px-6 py-4">Client Id</th>
                                <th scope="col" class="px-6 py-4">Number of Payment</th>
                                <th scope="col" class="px-6 py-4">First Payment Date</th>
                                <th scope="col" class="px-6 py-4">Last Payment Date</th>
                                <th scope="col" class="px-6 py-4">Loan Amount</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $loan)
                                <tr class="border-b border-neutral-200 dark:border-white/10">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $loan->clientid }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ $loan->num_of_payment }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ $loan->first_payment_date }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ $loan->last_payment_date }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ $loan->loan_amount }}</td>
                                </tr>
                                @endforeach
                              
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                   {{-- table end --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
