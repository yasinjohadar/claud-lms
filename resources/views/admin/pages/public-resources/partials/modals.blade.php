@foreach($resources as $resource)
    <x-admin.confirm-modal
        :id="'deletePublicResource' . $resource->id"
        title="تأكيد حذف المورد"
        message="سيتم حذف الملف المرتبط إن وُجد. لا يمكن التراجع عن هذا الإجراء."
        :subject="$resource->title"
        :subject-meta="$resource->type_label"
        icon="ri-folder-open-line"
        :action="route('admin.public-resources.destroy', $resource)"
        method="DELETE"
        confirm-text="نعم، احذف المورد"
    />
@endforeach
