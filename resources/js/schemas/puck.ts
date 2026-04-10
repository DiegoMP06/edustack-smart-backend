import { z } from "zod";

export const AccordionSchema = {
    title: z.string().optional(),
    items: z.array(
        z.object({
            question: z.string().min(1),
            answer: z.string().min(1),
        }),
    ),
    multiple: z.boolean(),
    variant: z.union([
        z.literal("default"),
        z.literal("separated"),
        z.literal("bordered"),
    ]),
};

export const BlogImageSchema = z.object({
    url: z.url(),
    alt: z.string().min(1),
    caption: z.string().optional(),
    alignment: z.union([
        z.literal("left"),
        z.literal("center"),
        z.literal("right"),
    ]),
    maxWidth: z.union([
        z.literal("sm"),
        z.literal("md"),
        z.literal("lg"),
        z.literal("full"),
    ]),
    rounded: z.boolean(),
    shadow: z.boolean(),
});

export const ButtonLinkSchema = z.object({
    label: z.string().min(1),
    href: z.url(),
    variant: z.union([
        z.literal("primary"),
        z.literal("secondary"),
        z.literal("outline"),
        z.literal("ghost"),
        z.literal("danger"),
    ]),
    size: z.union([z.literal("sm"), z.literal("md"), z.literal("lg")]),
    align: z.union([
        z.literal("left"),
        z.literal("center"),
        z.literal("right"),
        z.literal("full"),
    ]),
    newTab: z.boolean(),
    icon: z.string().optional(),
});

export const CalloutBoxSchema = z.object({
    type: z.union([
        z.literal("info"),
        z.literal("warning"),
        z.literal("danger"),
        z.literal("success"),
        z.literal("tip"),
    ]),
    title: z.string().optional(),
    content: z.string().min(1),
    showIcon: z.boolean(),
});

export const CodeBlockSchema = z.object({
    lang: z.string(),
    showCopyButton: z.boolean(),
    children: z.string(),
});

export const DividerSchema = z.object({
    label: z.string(),
    style: z.union([
        z.literal("solid"),
        z.literal("dashed"),
        z.literal("dotted"),
        z.literal("gradient"),
    ]),
    spacing: z.union([z.literal("sm"), z.literal("md"), z.literal("lg")]),
});

export const ExternalLinkSchema = z.object({
    children: z.string(),
    href: z.url(),
});

export const GallerySchema = z.object({
    images: z.array(
        z.object({
            url: z.url(),
            alt: z.string().min(1),
            caption: z.string().optional(),
        }),
    ),
    columns: z.number().refine((n) => [2, 3, 4].includes(n), {
        message: "Columns must be 2, 3, or 4",
    }),
    gap: z.union([z.literal("sm"), z.literal("md"), z.literal("lg")]),
    rounded: z.boolean(),
    lightbox: z.boolean(),
});

export const GridContainerSchema = z.object({
    columns: z.number().refine((n) => n >= 1 && n <= 12, {
        message: "Columns must be between 1 and 12",
    }),
    gap: z.number().min(0),
    items: z.array(
        z.object({
            content: z.any(),
        }),
    ),
    stackOnMobile: z.boolean().default(true),
});

export const HeadingSchema = z.object({
    children: z.string(),
    Tag: z.union([
        z.literal("h1"),
        z.literal("h2"),
        z.literal("h3"),
        z.literal("h4"),
        z.literal("h5"),
        z.literal("h6"),
    ]),
    align: z.union([
        z.literal("left"),
        z.literal("center"),
        z.literal("right"),
    ]),
    color: z.union([
        z.literal("default"),
        z.literal("muted"),
        z.literal("primary"),
        z.literal("accent"),
    ]),
});

export const ParagraphSchema = z.object({
    align: z.union([
        z.literal("left"),
        z.literal("center"),
        z.literal("right"),
        z.literal("justify"),
    ]),
    size: z.union([z.literal("sm"), z.literal("base"), z.literal("lg")]),
    color: z.union([z.literal("default"), z.literal("muted")]),
    children: z.string(),
});

export const QuoteSchema = z.object({
    author: z.string().optional(),
    source: z.string().optional(),
    variant: z.union([
        z.literal("info"),
        z.literal("warning"),
        z.literal("success"),
        z.literal("default"),
    ]),
    children: z.string(),
});

export const RichTextBlockSchema = z.object({
    html: z.string(),
});

export const StatsGridSchema = {
    items: z.array(
        z.object({
            value: z.string(),
            label: z.string(),
            description: z.string().optional(),
            icon: z.string().optional(),
        }),
    ),
    columns: z.union([z.literal(2), z.literal(3), z.literal(4)]),
    variant: z.union([
        z.literal("default"),
        z.literal("card"),
        z.literal("minimal"),
    ]),
    align: z.union([z.literal("left"), z.literal("center")]),
};

export const TimelineProps = z.object({
    items: z.array(
        z.object({
            date: z.string(),
            title: z.string(),
            description: z.string(),
            tag: z.string(),
            tagColor: z.string(),
        }),
    ),
    layout: z.union([z.literal("left"), z.literal("alternate")]),
});

export const VideoEmbedSchema = z.object({
    url: z.url(),
    caption: z.string().optional(),
    aspectRatio: z.union([
        z.literal("16/9"),
        z.literal("4/3"),
        z.literal("1/1"),
    ]),
    autoplay: z.boolean().default(false),
});

const PuckBlockSchema = z.object({
    type: z.string(),
    props: z.record(z.string(), z.any()).and(
        z.object({
            id: z.string(),
        }),
    ),
});

export const PuckContentSchema = z.array(PuckBlockSchema);

export const PuckSchema = z.object({
    content: PuckContentSchema,
});
