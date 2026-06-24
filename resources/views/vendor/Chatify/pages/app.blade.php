<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SLTB — Messages</title>

    {{-- Vite Assets (Code 1) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Chatify's head links --}}
    @include('Chatify::layouts.headLinks')
    <link rel="stylesheet" href="{{ asset('css/chatify/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        /* ── Integrate Chatify into the SLTB dashboard shell ─────────── */

        /* Target both the ID and class to ensure layout overrides apply perfectly */
        #chatify,
        .messenger {
            height: calc(100vh - 96px) !important;
            /* Adjusted slightly to give breathing room */
            max-height: calc(100vh - 96px) !important;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            display: flex !important;
            flex-direction: row;
            position: relative;
        }

        .messenger .messenger-messagingView {
            background: #f9fafb;
            display: flex;
            flex-direction: column;
            height: 100% !important;
            overflow: hidden;
        }

        .messenger .messenger-messagingView .m-body {
            flex: 1 1 auto !important;
            overflow-y: auto !important;
        }

        .messenger .messenger-messagingView .messenger-sendCard {
            position: relative !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 100% !important;
            flex-shrink: 0 !important;
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 10px 15px !important;
        }

        /* Match sidebar colours */
        .messenger .messenger-listView {
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
        }

        /* Header area */
        .messenger .messenger-listView .m-header {
            background: #1e3a5f;
            color: #ffffff;
            padding: 16px;
        }

        .messenger .messenger-listView .m-header input {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 13px;
        }

        .messenger .messenger-listView .m-header input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Contact list items */
        .messenger .listView-human .human-info span {
            font-size: 13px;
            font-weight: 500;
        }

        /* Message bubbles */
        .messenger .message-card.me .message {
            background: #2563eb;
            color: #fff;
            border-radius: 12px 12px 2px 12px;
        }

        .messenger .message-card.you .message {
            background: #ffffff;
            color: #1f2937;
            border-radius: 12px 12px 12px 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        }

        /* Send button */
        .messenger .send-icon {
            color: #2563eb;
        }

        .messenger .send-icon:hover {
            color: #1d4ed8;
        }

        /* Active/online indicator */
        .messenger .active-status {
            background: #22c55e;
        }

        /* Role badge in contact list */
        .sltb-role-badge {
            font-size: 10px;
            padding: 1px 6px;
            border-radius: 99px;
            background: #dbeafe;
            color: #1d4ed8;
            margin-left: 4px;
            font-weight: 500;
        }
    </style>
    @yield('styles')
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">

        {{-- SLTB sidebar (Code 1 Component) --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col flex-shrink-0">
            <div class="px-5 py-4 border-b border-gray-200">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="h-10 w-auto flex items-center justify-center flex-shrink-0">
                        <img src="{{ asset('images/sltb-logo.png') }}" alt="SLTB Logo"
                            class="w-full h-full object-contain">
                    </div>
                    <!-- <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div> -->
                    <div>
                        <span class="text-sm font-semibold text-gray-800 block leading-tight">SLTB Yatinuwara</span>
                        <span class="text-xs text-gray-400 leading-tight">
                            {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                        </span>
                    </div>
                </a>
            </div>
            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <x-sidebar />
            </nav>
            <div class="px-4 py-4 border-t border-gray-200">
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar"
                        class="w-8 h-8 rounded-full object-cover border border-gray-200 flex-shrink-0">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->employee_id }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-xs text-red-500 hover:text-red-700">Sign out</button>
                </form>
            </div>
        </aside>

        {{-- Main area: Dashboard header + Integrated Chatify Layout --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <h1 class="text-xl font-semibold text-gray-800">Messages</h1>
                <div class="flex items-center gap-4">
                    <a href="{{ route('notifications.index') }}" class="relative text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a1 1 0 10-2 0v1.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @php
                            $unread = \App\Models\SltbNotification::where('user_id', auth()->id())
                                ->where('is_read', false)->count();
                        @endphp
                        @if($unread > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs
                                         rounded-full flex items-center justify-center leading-none">
                                {{ $unread > 9 ? '9+' : $unread }}
                            </span>
                        @endif
                    </a>
                    <span class="text-gray-400 text-sm">{{ now()->format('D, d M Y') }}</span>
                </div>
            </header>

            {{-- Main Chatify Workspace Container --}}
            <main class="flex-1 overflow-hidden p-4">

                {{-- FIX: Added id="chatify" so Chatify's Javascript engine can find the view layout tags properly --}}
                <div id="chatify" class="messenger">
                    {{-- ----------------------Users/Groups lists side---------------------- --}}
                    <div class="messenger-listView {{ !empty($id) ? 'conversation-active' : '' }}">
                        {{-- Header and search bar --}}
                        <div class="m-header">
                            <nav>
                                <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">SLTB
                                        MESSAGES</span> </a>
                                {{-- header buttons --}}
                                <nav class="m-header-right">
                                    <a href="#"><i class="fas fa-cog settings-btn"></i></a>
                                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                                </nav>
                            </nav>
                            {{-- Search input --}}
                            <input type="text" class="messenger-search" placeholder="Search" />
                        </div>

                        {{-- tabs and lists --}}
                        <div class="m-body contacts-container">
                            {{-- Lists [Users/Group] --}}
                            {{-- ---------------- [ User Tab ] ---------------- --}}
                            <div class="show messenger-tab users-tab app-scroll" data-view="users">
                                {{-- Favorites --}}
                                <div class="favorites-section">
                                    <p class="messenger-title"><span>Favorites</span></p>
                                    <div class="messenger-favorites app-scroll-hidden"></div>
                                </div>
                                {{-- Saved Messages --}}
                                <p class="messenger-title"><span>Your Space</span></p>
                                {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!}
                                {{-- Contact --}}
                                <p class="messenger-title"><span>All Messages</span></p>
                                <div class="listOfContacts"
                                    style="width: 100%;height: calc(100% - 272px);position: relative;"></div>
                            </div>

                            {{-- ---------------- [ Search Tab ] ---------------- --}}
                            <div class="messenger-tab search-tab app-scroll" data-view="search">
                                {{-- items --}}
                                <p class="messenger-title"><span>Search</span></p>
                                <div class="search-records">
                                    <p class="message-hint center-el"><span>Type to search..</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ----------------------Messaging side---------------------- --}}
                    <div class="messenger-messagingView">
                        {{-- header title [conversation name] amd buttons --}}
                        <div class="m-header m-header-messaging">
                            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                                {{-- header back button, avatar and user name --}}
                                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                                    <div class="avatar av-s header-avatar"
                                        style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                                    </div>
                                    <a href="#" class="user-name">{{ config('chatify.name') }}</a>
                                </div>
                                {{-- header buttons --}}
                                <nav class="m-header-right">
                                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                                    <a href="/"><i class="fas fa-home"></i></a>
                                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                                </nav>
                            </nav>
                            {{-- Internet connection --}}
                            <div class="internet-connection">
                                <span class="ic-connected">Connected</span>
                                <span class="ic-connecting">Connecting...</span>
                                <span class="ic-noInternet">No internet access</span>
                            </div>
                        </div>

                        {{-- Messaging area --}}
                        <div class="m-body messages-container app-scroll">
                            <div class="messages">
                                <p class="message-hint center-el"><span>Please select a chat to start messaging</span>
                                </p>
                            </div>
                            {{-- Typing indicator --}}
                            <div class="typing-indicator">
                                <div class="message-card typing">
                                    <div class="message">
                                        <span class="typing-dots">
                                            <span class="dot dot-1"></span>
                                            <span class="dot dot-2"></span>
                                            <span class="dot dot-3"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Send Message Form Form Input --}}
                        @include('Chatify::layouts.sendForm')
                    </div>

                    {{-- ---------------------- Info side ---------------------- --}}
                    <div class="messenger-infoView app-scroll">
                        {{-- nav actions --}}
                        <nav>
                            <p>User Details</p>
                            <a href="#"><i class="fas fa-times"></i></a>
                        </nav>
                        {!! view('Chatify::layouts.info')->render() !!}
                    </div>
                </div>

            </main>
        </div>
    </div>

    @include('Chatify::layouts.modals')

    {{-- FIX: Order script attachments exactly as expected by Chatify engine --}}
    <script src="{{ asset('js/chatify/jquery.min.js') }}"></script>
    @include('Chatify::layouts.footerLinks')
    @yield('scripts')

</body>

</html>