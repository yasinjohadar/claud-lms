@foreach($members as $member)
    <x-admin.confirm-modal
        :id="'deleteTeamMember' . $member->id"
        title="تأكيد حذف عضو الفريق"
        message="سيتم إزالة العضو من الصفحة الرئيسية وصفحة من نحن."
        :subject="$member->display_name"
        :subject-meta="$member->role_title"
        icon="ri-team-line"
        :action="route('admin.team-members.destroy', $member)"
        method="DELETE"
        confirm-text="نعم، احذف العضو"
    />
@endforeach
