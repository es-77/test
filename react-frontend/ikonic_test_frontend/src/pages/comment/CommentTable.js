import React, { useEffect, useState } from 'react'
import DataTable from '../DataTable';
import { useComment, useDeleteComment } from '../../hooks/commentQueries';
import RichTextEditor from '../Editor/RichTextEditor';

const columns = [
    { field: 'userName', headerName: 'User Name', width: 130 },
    { field: 'userEmail', headerName: 'User Email', width: 130 },
    { field: 'feedBackTitle', headerName: 'Feed Back Title', width: 130 },
    { field: 'category', headerName: 'Category', width: 130 },
    { field: 'content', headerName: 'Content', width: 130 },
];

const dropdown = [
    { label: 'Delete', action: "delete" },
    { label: 'Update', action: "update" },
];

function CommentTable() {
    const commentQuery = useComment({});
    const commentdeleteQuery = useDeleteComment();
    const [admin, setAdmins] = useState([]);
    useEffect(() => {
        if (commentQuery.isFetched && commentQuery.data) {
            setAdmins(commentQuery.data);
        }
    }, [commentQuery.data, commentQuery.isFetched, commentQuery.isFetching]);

    const handleAction = (params, action) => {
        if (action === 'delete') {
            commentdeleteQuery.mutate(params?.id);
        }
    }
    return (
        <div>
            <DataTable columns={columns} admin={admin} dropdown={dropdown} handleAction={handleAction} listName="Comment List" navgatePath="/dashboard/feedback_form" buttonText="Add Comment" />
        </div>
    )
}

export default CommentTable