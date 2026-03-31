import type { ComponentProps, CSSProperties } from 'react';
import { cn } from '@/lib/utils';

export default function TipTapEditorContainer({
    children,
    className,
    style,
    ...props
}: ComponentProps<'div'> & { style?: CSSProperties }) {
    return (
        <div
            className={cn(
                'bg-background p-3 text-foreground',
                '[&_ul,ol]:my-5 [&_ul,ol]:ml-[0.4rem] [&_ul,ol]:px-4',
                '[&_li_p]:my-[0.25em] [&_ol]:list-decimal [&_ul]:list-disc',
                '[&_h1,h2,h3,h4,h5,h6]:mt-6 [&_h1,h2,h3,h4,h5,h6]:mb-4 [&_h1,h2,h3,h4,h5,h6]:leading-normal',
                '[&_h1,h2,h3,h4,h5,h6]:font-bold [&_h1,h2,h3,h4,h5,h6]:text-foreground',
                '[&_h1]:text-3xl [&_h2]:text-2xl [&_h3]:text-xl [&_h4]:text-lg [&_h5]:text-base [&_h6]:text-sm',
                '[&_p]:my-4 [&_p]:text-justify [&_p]:text-base [&_p]:leading-relaxed [&_p]:text-accent-foreground',
                '[&_a]:cursor-pointer [&_a]:text-sky-400 [&_a]:underline [&_a]:transition-colors [&_a]:hover:text-sky-500',
                '[&_code]:rounded [&_code]:bg-purple-950 [&_code]:text-sm [&_code]:text-cyan-300',
                '[&_code]:border [&_code]:border-purple-500 [&_code]:px-[0.3em] [&_code]:py-[0.25em] [&_code]:font-mono',
                '[&_pre]:mx-4 [&_pre]:my-6 [&_pre]:overflow-x-auto [&_pre]:rounded [&_pre]:border [&_pre]:border-purple-500 [&_pre]:bg-purple-950 [&_pre]:p-4 [&_pre]:font-mono',
                '[&_pre_code]:rounded-none [&_pre_code]:border-0 [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_pre_code]:text-sm [&_pre_code]:text-cyan-300',
                '[&_blockquote]:mx-4 [&_blockquote]:rounded [&_blockquote]:border-l-4 [&_blockquote]:border-muted-foreground',
                '[&_blockquote]:my-6 [&_blockquote]:bg-accent [&_blockquote]:py-2 [&_blockquote]:pr-2 [&_blockquote]:pl-6 [&_blockquote]:text-lg [&_blockquote]:text-muted-foreground',
                '[&_blockquote_p]:m-0 [&_blockquote_p]:leading-normal',
                '[&_hr]:mx-4 [&_hr]:my-6 [&_hr]:border-t-2 [&_hr]:border-muted-foreground',
                '[&_img]:mx-auto [&_img]:my-4 [&_img]:block [&_img]:max-w-full [&_img]:rounded',
                '[&_img.ProseMirror-selectednode]:ring-2 [&_img.ProseMirror-selectednode]:ring-indigo-500',
                '[&_iframe]:my-6 [&_iframe]:aspect-video [&_iframe]:w-full [&_iframe]:rounded [&_iframe]:border-0',
                '[&_table]:my-6 [&_table]:w-full [&_table]:border-collapse [&_table]:text-sm',
                '[&_td]:border [&_td]:border-border [&_td]:px-3 [&_td]:py-2 [&_td]:align-top',
                '[&_th]:border [&_th]:border-border [&_th]:px-3 [&_th]:py-2 [&_th]:align-top',
                '[&_th]:bg-muted [&_th]:font-semibold [&_th]:text-foreground',
                '[&_.selectedCell]:bg-indigo-100',
                '[&_.column-resize-handle]:absolute [&_.column-resize-handle]:top-0 [&_.column-resize-handle]:right-[-2px] [&_.column-resize-handle]:bottom-0 [&_.column-resize-handle]:w-1 [&_.column-resize-handle]:cursor-col-resize [&_.column-resize-handle]:bg-indigo-400',
                '[&_ul[data-type=taskList]]:list-none [&_ul[data-type=taskList]]:pl-0',
                '[&_li[data-type=taskItem]]:my-1 [&_li[data-type=taskItem]]:flex [&_li[data-type=taskItem]]:items-start [&_li[data-type=taskItem]]:gap-2',
                '[&_li[data-type=taskItem]>label]:mt-0.5 [&_li[data-type=taskItem]>label]:flex [&_li[data-type=taskItem]>label]:items-center',
                '[&_li[data-type=taskItem]>label>input]:size-4 [&_li[data-type=taskItem]>label>input]:rounded [&_li[data-type=taskItem]>label>input]:accent-indigo-600',
                '[&_li[data-type=taskItem][data-checked=true]>div]:line-through [&_li[data-type=taskItem][data-checked=true]>div]:opacity-50',
                '[&_.is-editor-empty:first-child::before]:content-[attr(data-placeholder)]',
                '[&_.is-editor-empty:first-child::before]:text-muted-foreground',
                '[&_.is-editor-empty:first-child::before]:pointer-events-none [&_.is-editor-empty:first-child::before]:float-left [&_.is-editor-empty:first-child::before]:h-0',
                '[&_.ProseMirror]:outline-none',
                '[&>*:first-child]:mt-0',
                className,
            )}
            style={style}
            {...props}
        >
            {children}
        </div>
    );
}
