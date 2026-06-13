@foreach($students as $student)
    <x-admin.confirm-modal
        :id="'deleteStudent' . $student->id"
        variant="danger"
        icon="ri-delete-bin-line"
        title="حذف الطالب"
        :message="'هل أنت متأكد من حذف ' . ($student->user?->name ?? 'هذا الطالب') . '؟ سيتم حذف حساب المستخدم أيضاً.'"
        confirm-text="نعم، احذف"
        :action="route('admin.students.destroy', $student)"
        method="DELETE"
    />
@endforeach
