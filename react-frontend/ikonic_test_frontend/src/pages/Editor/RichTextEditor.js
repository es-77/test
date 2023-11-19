import React, { useRef, useEffect } from "react";
import { Editor } from "@tinymce/tinymce-react";
import { useSaveComment } from "../../hooks/commentQueries";
import { toast } from 'react-toastify';
import { useNavigate } from "react-router-dom";
import { Button } from "@mui/material";
import SendIcon from '@mui/icons-material/Send';

export default function RichTextEditor({ feedbackId }) {
    const editorRef = useRef(null);
    const navigate = useNavigate();
    const commentQuery = useSaveComment();

    useEffect(() => {
        if (editorRef.current) {
            editorRef.current.on("keydown", handleKeyDown);
        }
    }, []);

    const handleKeyDown = (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            if (editorRef.current) {
                console.log("Content:", editorRef.current.getContent());
            }
        }
    };

    const handleSendMessage = () => {
        if (editorRef.current) {
            const content = editorRef.current.getContent();
            const comment = {
                feedbackId: feedbackId,
                content: content
            }
            console.log(comment);
            commentQuery.mutate(comment);
        }
    };


    useEffect(() => {
        const data = commentQuery?.data;
        const errors = commentQuery?.error?.response?.data?.message;
        if (commentQuery.isSuccess) {
            const message = data?.message;
            const messages = data?.message;
            const response = data?.response;
            if (response === 101) {
                toast.success(message);
                // setTimeout(() => {
                //     navigate('/dashboard/user', { replace: true });
                // }, 2000);
                if (editorRef?.current) {
                    editorRef.current.setContent('');
                }
            } else {
                toast.error(messages);
                // Object.keys(validationErrors).forEach((key) => {
                //     toast.error(validationErrors[key][0]);
                // });
            }
        }

        if (commentQuery.isError) {
            const message = 'Error occurred while saving the data';
            toast.error(errors ?? message);
        }
    }, [commentQuery.isSuccess, commentQuery.isError]);

    return (
        <>
            <Editor
                // initialValue="<p>This is the initial content of the editor.</p>"
                onInit={(evt, editor) => (editorRef.current = editor)}
                init={{
                    height: 200,
                    menubar: false,
                    plugins: [
                        "advlist",
                        "autolink",
                        "lists",
                        "link",
                        "image",
                        "charmap",
                        "preview",
                        "anchor",
                        "searchreplace",
                        "visualblocks",
                        "code",
                        "fullscreen",
                        "insertdatetime",
                        "media",
                        "table",
                        "code",
                        "wordcount",
                        "emoticons",
                    ],
                    toolbar:
                        "undo redo | blocks | emoticons|" +
                        "bold italic forecolor | alignleft aligncenter " +
                        "alignright alignjustify | bullist numlist outdent indent | " +
                        "removeformat",
                    content_style:
                        "body { font-family: Helvetica, Arial, sans-serif; font-size: 14px }",
                }}
            />
            <Button onClick={handleSendMessage} sx={{ marginTop: 2, marginBottom: 2, width: '100%' }} variant="contained" endIcon={<SendIcon />}>
                Send Message
            </Button>
        </>
    );
}
