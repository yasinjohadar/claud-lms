{{--
    زر تفعيل/إيقاف في الجداول — يعمل مع admin-ui.js و x-admin.toggle-status-modal.

    <x-admin.activation-toggle
        :entity-id="$user->id"
        :is-active="$user->is_active"
        :subject="$user->name"
        :subject-meta="$user->email"
    />
--}}
@props([
    'entityId',
    'isActive' => false,
    'subject' => '',
    'subjectMeta' => null,
    'entityType' => 'user',
    'activeLabel' => 'نشط',
    'inactiveLabel' => 'غير نشط',
    'disabled' => false,
])

<label @class([
    'activation-pill mb-0',
    'activation-pill--active' => $isActive,
    'activation-pill--inactive' => ! $isActive,
])>
    <input type="checkbox"
           class="toggle-status"
           data-entity-id="{{ $entityId }}"
           data-entity-type="{{ $entityType }}"
           data-subject-name="{{ $subject }}"
           @if($subjectMeta) data-subject-meta="{{ $subjectMeta }}" @endif
           data-active-label="{{ $activeLabel }}"
           data-inactive-label="{{ $inactiveLabel }}"
           @disabled($disabled)
           @checked($isActive)>
    <i class="ri-shut-down-line"></i>
    <span class="toggle-label">{{ $isActive ? $activeLabel : $inactiveLabel }}</span>
</label>
