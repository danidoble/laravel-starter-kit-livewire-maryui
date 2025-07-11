<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DropdownCustom extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        public ?string $icon = 'o-chevron-down',
        public ?bool $right = false,
        public ?bool $top = false,
        public ?bool $noXAnchor = false,
        public ?string $classContainer = null,
        public ?string $classPop = null,
        // Slots
        public mixed $trigger = null
    ) {
        $this->uuid = 'mary'.md5(serialize($this)).$id;
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <details
                x-data="{open: false}"
                @click.outside="open = false"
                :open="open"
                @class([
                    'dropdown '.($classContainer ?? ''),
                    'dropdown-end' => ($noXAnchor && $right),
                    'dropdown-top' => ($noXAnchor && $top),
                    'dropdown-bottom' => $noXAnchor,
                ])
            >
                <!-- CUSTOM TRIGGER -->
                @if($trigger)
                    <summary x-ref="button" @click.prevent="open = !open" {{ $trigger->attributes->class(['list-none']) }}>
                        {{ $trigger }}
                    </summary>
                @else
                    <!-- DEFAULT TRIGGER -->
                    <summary x-ref="button" @click.prevent="open = !open" {{ $attributes->class(["btn"]) }}>
                        {{ $label }}
                        <x-mary-icon :name="$icon" />
                    </summary>
                @endif

                <ul
                    @class([
                        'p-2','shadow','menu','z-[1]','border-[length:var(--border)]','border-base-content/10','bg-base-100', 'rounded-box','w-full','max-w-64','min-w-32',
                        'dropdown-content' => $noXAnchor,
                        $classPop,
                    ])
                    @click="open = false"
                    @if(!$noXAnchor)
                        x-anchor.{{ $right ? 'bottom-end' : 'bottom-start' }}="$refs.button"
                    @endif
                >
                    <div wire:key="dropdown-slot-{{ $uuid }}">
                        {{ $slot }}
                    </div>
                </ul>
            </details>
        HTML;
    }
}
