<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MainCustom extends Component
{
    public string $url;

    public function __construct(

        // Slots
        public mixed $sidebar = null,
        public mixed $sidebarFooter = null,
        public mixed $content = null,
        public mixed $footer = null,
        public ?bool $fullWidth = false,
        public ?bool $withNav = false,
        public ?string $collapseText = 'Collapse',
        public ?string $collapseIcon = 'o-bars-3-bottom-right',
        public ?bool $collapsible = false,
    ) {
        $this->url = route('mary.toogle-sidebar', absolute: false);
    }

    public function render(): View|Closure|string
    {
        return <<<'BLADE'
                 <main @class(["w-full mx-auto", "max-w-screen-2xl" => !$fullWidth])>
                    <div @class([
                        "drawer lg:drawer-open",
                        "drawer-end" => $sidebar?->attributes['right'],
                        "max-sm:drawer-end" => $sidebar?->attributes['right-mobile'],
                    ])>
                        <input id="{{ $sidebar?->attributes['drawer'] }}" type="checkbox" class="drawer-toggle" />
                        <div {{ $content->attributes->class(["drawer-content w-full mx-auto p-5 lg:px-10 lg:py-5"]) }}>
                            {{-- MAIN CONTENT  --}}
                            {{ $content }}
                        </div>

                        {{-- SIDEBAR  --}}
                        @if($sidebar)
                            <div
                                x-data="{
                                    collapsed: {{ session('mary-sidebar-collapsed', 'false') }},
                                    collapseText: '{{ $collapseText }}',
                                    toggle() {
                                        this.collapsed = !this.collapsed;
                                        fetch('{{ $url }}?collapsed=' + this.collapsed);
                                        this.$dispatch('sidebar-toggled', this.collapsed);
                                    }
                                }"

                                @menu-sub-clicked="if(collapsed) { toggle() }"
                                @class(["bg-base-200 drawer-side z-20 lg:z-auto", "top-0 lg:top-[65px] lg:h-[calc(100vh-65px)]" => $withNav])
                            >
                                <label for="{{ $sidebar?->attributes['drawer'] }}" aria-label="close sidebar" class="drawer-overlay"></label>

                                {{-- SIDEBAR CONTENT  --}}
                                <div
                                    :class="collapsed
                                        ? '!w-[62px] [&>*_summary::after]:!hidden [&_.mary-hideable]:!hidden [&_.display-when-collapsed]:!block [&_.hidden-when-collapsed]:!hidden'
                                        : '!w-[270px] [&>*_summary::after]:!block [&_.mary-hideable]:!block [&_.hidden-when-collapsed]:!block [&_.display-when-collapsed]:!hidden'"

                                    {{
                                        $sidebar->attributes->class([
                                            "flex flex-col !transition-all !duration-100 ease-out overflow-x-hidden overflow-y-auto h-screen",
                                            "w-[62px] [&>*_summary::after]:hidden [&_.mary-hideable]:hidden [&_.display-when-collapsed]:block [&_.hidden-when-collapsed]:hidden" => session('mary-sidebar-collapsed') == 'true',
                                            "w-[270px] [&>*_summary::after]:block [&_.mary-hideable]:block [&_.hidden-when-collapsed]:block [&_.display-when-collapsed]:hidden" => session('mary-sidebar-collapsed') != 'true',
                                            "lg:h-[calc(100vh-65px)]" => $withNav
                                        ])
                                     }}
                                >
                                    <div class="flex-1">
                                        {{ $sidebar }}
                                    </div>

                                    @if($sidebarFooter)
                                    <div class="">
                                        {{ $sidebarFooter }}
                                    </div>
                                    @endif

                                     {{-- SIDEBAR COLLAPSE  --}}
                                    @if($sidebar->attributes['collapsible'])
                                    <x-mary-menu class="hidden lg:block">
                                        <x-mary-menu-item
                                            @click="toggle"
                                            icon="{{ $sidebar->attributes['collapse-icon'] ?? $collapseIcon }}"
                                            title="{{ $sidebar->attributes['collapse-text'] ?? $collapseText }}" />
                                    </x-mary-menu>
                                    @endif
                                </div>
                            </div>
                        @endif
                        {{-- END SIDEBAR --}}

                    </div>
                </main>

                 {{-- FOOTER  --}}
                 @if($footer)
                    <footer {{ $footer?->attributes->class(["mx-auto w-full", "max-w-screen-2xl" => !$fullWidth ]) }}>
                        {{ $footer }}
                    </footer>
                @endif
                BLADE;
    }
}
