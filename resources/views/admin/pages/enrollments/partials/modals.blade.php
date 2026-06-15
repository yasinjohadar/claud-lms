@foreach($enrollments as $enrollment)
    @if($enrollment->status !== 'cancelled')
        @php
            $studentName = $enrollment->student?->user?->name ?? 'الطالب';
            $courseTitle = $enrollment->course?->title ?? 'الكورس';
        @endphp
        <x-admin.confirm-modal
            :id="'cancelEnrollment' . $enrollment->id"
            title="إلغاء التسجيل"
            message="سيتم إلغاء وصول الطالب إلى هذا الكورس. يمكنك إعادة التسجيل لاحقاً."
            :subject="$studentName . ' — ' . $courseTitle"
            :action="route('admin.enrollments.destroy', $enrollment)"
            method="DELETE"
            variant="warning"
            confirm-text="نعم، ألغِ التسجيل"
        />
    @endif
@endforeach
