<tr>
    <td>- {{ $pr->code }} <span style="font-style: italic;">(สำเนาการแก้ไข)</span></td>
    <td>{{ $pr->document_at->format('d/m/Y H:i:s') }}</td>
    <td>
        @if($pr->created_user_id != 0)
            <span class="label label-primary">{{ $pr->createdUser->username }}</span>
        @endif
    </td>
    <td>
        @if($pr->edit_user_id != 0)
            <span class="label label-warning">{{ $pr->editUser->username }}</span>
        @endif
    </td>
    <td>
        @if($pr->deleted_user_id != 0)
            <span class="label label-plain">{{ $pr->deletedUser->username }}</span>
        @endif
    </td>
    <td>
        @if($pr->approve_user_id != 0)
            <span class="label label-success">{{ $pr->approveUser->username }}</span>
        @endif
    </td>
    <td>
        @if($pr->none_approve_user_id != 0)
            <span class="label label-danger">{{ $pr->noneApproveUser->username }}</span>
        @endif
    </td>
    <td><a href="{{ route('whs.prs-report.show', $pr->id) }}" class="btn btn-info btn-xs">ดูรายละเอียด</td>
</tr>
@if($pr->parentPr != null)
    @include('whs.prs.closure', ['pr' => $pr->parentPr])
@endif
