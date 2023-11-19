import React from 'react'
import RichTextEditor from '../Editor/RichTextEditor'

function CommentForm() {
    const feedbackId = 12;
    return (
        <div>
            <RichTextEditor feedbackId={feedbackId} />
        </div>
    )
}

export default CommentForm