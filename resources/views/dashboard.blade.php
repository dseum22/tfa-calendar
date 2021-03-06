@php
use App\Models\Subject;
@endphp

@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.css" integrity="sha256-uq9PNlMzB+1h01Ij9cx7zeE2OR2pLAfRw3uUUOOPKdA=" crossorigin="anonymous">
@endsection

@section('content')
<div id="calendar"></div>
<div class="fixed inset-0 overflow-y-auto z-30 hidden" id="modalContainer" x-data="{open:false,@if (Auth::user()->role->name == 'tutor') createSlotModal:false, deleteSlotModal:false, @elseif (Auth::user()->role->name == 'student') claimSlotModal: false, unclaimSlotModal:false @endif}">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" x-show="open">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
        @if (Auth::user()->role->name == 'tutor')
        <div @click.away="toggleModal('createSlot')" x-show="createSlotModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true">
            <form id="createSlotForm" novalidate>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Create slot
                            </h3>
                            <div class="mt-2">
                                <div class="mb-3">
                                    <input type="datetime-local" class="form-element" name="start" required>
                                    <div class="invalid-feedback">
                                        Slot must be created at least 6 hours in advance.
                                    </div>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" name="subject_id">
                                        @foreach(json_decode(Auth::user()->tutor->subjects) as $subject)
                                        <option value="{{ $subject }}">{{ Subject::find($subject)->name }}</option>
                                        @endforeach
                                    </select>
                                    <label>Subject</label>
                                </div>
                                <div class="flex">
                                    <input class="form-check mt-1" type="checkbox" name="repeat" id="repeatCreate">
                                    <label class="text-gray-700 ml-2" for="repeatCreate">Repeat this slot on this day of the week at this time for the next 20 weeks.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="btn-positive btn-modal sm:ml-3">
                        Create
                    </button>
                    <button type="button" class="mt-3 btn-neutral btn-modal sm:mt-0" @click="toggleModal('createSlot')">
                        Close
                    </button>
                </div>
            </form>
        </div>
        <div @click.away="toggleModal('deleteSlot')" x-show="deleteSlotModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true">
            <form id="deleteSlotForm" novalidate>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="mt-2">
                                <div class="mb-3">
                                    <input type="datetime-local" class="bg-white outline-none font-bold text-xl" name="start" disabled>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-7 w-7 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div><span name="student_name"></span><span>&nbsp;is learning&nbsp;</span></div><span name="subject_name"></span>
                                </div>
                                <div class="flex items-center mb-3">
                                    <svg class="h-7 w-7 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                    <span name="student_email"></span>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-flat" name="info" disabled></textarea>
                                    <label>What do they need help with?</label>
                                </div>
                                <div>
                                    <a class="btn-positive flex justify-center text-base tracking-wide w-full h-12" href="#" target="_blank" name="meeting_link"><svg class="my-auto h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg><span class="my-auto ml-2">Meeting link</span></a>
                                </div>
                                <div class="flex ml-2 pt-2">
                                    <input class="form-check mt-1" type="checkbox" name="repeat" id="repeatDelete">
                                    <label class="text-gray-700 ml-3" for="repeatDelete">Delete all repeating slots on this day
                                        of the week at this time after this slot</label>
                                </div>
                                <div class="hidden" name="id"></div>
                                <div class="hidden" name="claimed"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="btn-negative btn-modal sm:ml-3">
                        Delete
                    </button>
                    <button type="button" class="mt-3 btn-neutral btn-modal sm:mt-0" @click="toggleModal('deleteSlot')">
                        Close
                    </button>
                </div>
            </form>
        </div>
        @elseif (Auth::user()->role->name == 'student')
        <div @click.away="toggleModal('claimSlot')" x-show="claimSlotModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true">
            <form id="claimSlotForm" novalidate>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="mt-2">
                                <div class="mb-3">
                                    <input type="datetime-local" class="bg-white outline-none font-bold text-xl" name="start" disabled>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-7 w-7 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span name="tutor_name"></span><span>&nbsp;is tutoring&nbsp;</span><span name="subject_name"></span>
                                </div>
                                <div class="flex items-center mb-3">
                                    <svg class="h-7 w-7 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Speaks&nbsp;</span>
                                    <span name="tutor_languages"></span>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-flat" name="tutor_bio" disabled></textarea>
                                    <label>Tutor bio</label>
                                </div>
                                <div class="form-floating">
                                    <textarea class="form-element" name="info" required></textarea>
                                    <label>What do you need help with?</label>
                                </div>
                                <div class="hidden" name="id"></div>
                                <div class="hidden" name="claimed"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="btn-positive btn-modal sm:ml-3">
                        Claim
                    </button>
                    <button type="button" class="mt-3 btn-neutral btn-modal sm:mt-0" @click="toggleModal('claimSlot')">
                        Close
                    </button>
                </div>
            </form>
        </div>
        <div @click.away="toggleModal('unclaimSlot')" x-show="unclaimSlotModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true">
            <form id="unclaimSlotForm" novalidate>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="mt-2">
                                <div class="mb-3">
                                    <input type="datetime-local" class="bg-white outline-none font-bold text-xl" name="start" disabled>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-7 w-7 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span name="tutor_name"></span><span>&nbsp;is tutoring&nbsp;</span><span name="subject_name"></span>
                                </div>
                                <div class="flex items-center mb-2">
                                    <svg class="h-7 w-7 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Speaks&nbsp;</span>
                                    <span name="tutor_languages"></span>
                                </div>
                                <div class="flex items-center mb-3">
                                    <svg class="h-7 w-7 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                    <span name="tutor_email"></span>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-flat" name="tutor_bio" disabled></textarea>
                                    <label>Tutor bio</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <textarea class="form-flat" name="info" disabled></textarea>
                                    <label>What do you need help with?</label>
                                </div>
                                <div>
                                    <a class="btn-positive flex justify-center text-base tracking-wide w-full h-12" href="#" target="_blank" name="meeting_link"><svg class="my-auto h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg><span class="my-auto ml-2">Meeting link</span></a>
                                </div>
                                <div class="hidden" name="id"></div>
                                <div class="hidden" name="claimed"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="btn-negative btn-modal sm:ml-3">
                        Unclaim
                    </button>
                    <button type="button" class="mt-3 btn-neutral btn-modal sm:mt-0" @click="toggleModal('unclaimSlot')">
                        Close
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.5.1/main.min.js" integrity="sha256-rPPF6R+AH/Gilj2aC00ZAuB2EKmnEjXlEWx5MkAp7bw=" crossorigin="anonymous"></script>
<script src="{{ mix('js/dashboard.js') }}"></script>
@endsection