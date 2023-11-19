import React, { useRef, useEffect } from "react";
import { Editor } from "@tinymce/tinymce-react";

export default function RichTextEditor() {
    const editorRef = useRef(null);

    useEffect(() => {
        if (editorRef.current) {
            editorRef.current.on("keydown", handleKeyDown);
        }
    }, []);

    const handleKeyDown = (e) => {
        if (e.keyCode === 13) {
            e.preventDefault();
            if (editorRef.current) {
                alert(editorRef.current.getContent());
            }
        }
    };

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
                    placeholder: "Type your content here...",
                }}
            />
        </>
    );
}
