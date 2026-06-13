@php $tagColor = $tag->color ?? '#6366f1'; @endphp
<x-admin.confirm-modal
    :id="'deleteCourseTag' . $tag->id"
    title="تأكيد حذف التاغ"
    message="سيتم إزالة التاغ من جميع الكورسات المرتبطة به. لا يمكن التراجع عن هذا الإجراء."
    :subject="'#' . $tag->name"
    :subject-meta="$tag->courses_count . ' كورس مرتبط'"
    icon="ri-price-tag-3-line"
    :action="route('admin.courses.tags.destroy', $tag->id)"
    method="DELETE"
    confirm-text="نعم، احذف التاغ"
/>
