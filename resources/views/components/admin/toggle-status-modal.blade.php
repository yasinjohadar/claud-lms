{{--
    مودال تأكيد تفعيل/إيقاف — يُستخدم مع admin-ui.js (initActivationToggles + adminUiAjaxConfirm).

    <x-admin.toggle-status-modal id="userToggleStatusModal" />
    <x-admin.toggle-status-modal id="studentToggleStatusModal" entity-label="الطالب" />
--}}
@props([
    'id' => 'toggleStatusModal',
    'entityLabel' => 'المستخدم',
    'activateTitle' => null,
    'activateMessage' => null,
    'deactivateTitle' => null,
    'deactivateMessage' => null,
    'activateConfirmText' => 'نعم، فعّل',
    'deactivateConfirmText' => 'نعم، أوقف',
])

@php
    $activateTitle = $activateTitle ?? 'تأكيد تفعيل ' . $entityLabel;
    $activateMessage = $activateMessage ?? 'سيتمكن ' . $entityLabel . ' من الدخول واستخدام النظام.';
    $deactivateTitle = $deactivateTitle ?? 'تأكيد إيقاف ' . $entityLabel;
    $deactivateMessage = $deactivateMessage ?? 'لن يتمكن ' . $entityLabel . ' من الدخول إلى النظام حتى إعادة تفعيل حسابه.';
@endphp

<div data-toggle-status-modal
     data-entity-label="{{ $entityLabel }}"
     data-activate-title="{{ $activateTitle }}"
     data-activate-message="{{ $activateMessage }}"
     data-deactivate-title="{{ $deactivateTitle }}"
     data-deactivate-message="{{ $deactivateMessage }}"
     data-activate-confirm="{{ $activateConfirmText }}"
     data-deactivate-confirm="{{ $deactivateConfirmText }}">
    <x-admin.confirm-modal
        :id="$id"
        ajax-confirm
        variant="success"
        icon="ri-shut-down-line"
        :title="$activateTitle"
        :message="$activateMessage"
        :confirm-text="$activateConfirmText"
    />
</div>
