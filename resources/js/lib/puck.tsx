import type { Config } from '@puckeditor/core';

import type Accordion from '@/components/puck/Accordion';
import { AccordionConfig } from '@/components/puck/Accordion';
import type BlogImage from '@/components/puck/BlogImage';
import { BlogImageConfig } from '@/components/puck/BlogImage';
import type ButtonLink from '@/components/puck/ButtonLink';
import { ButtonLinkConfig } from '@/components/puck/ButtonLink';
import type CalloutBox from '@/components/puck/CalloutBox';
import { CalloutBoxConfig } from '@/components/puck/CalloutBox';
import type CodeBlock from '@/components/puck/CodeBlock';
import { CodeBlockConfig } from '@/components/puck/CodeBlock';
import type Divider from '@/components/puck/Divider';
import { DividerConfig } from '@/components/puck/Divider';
import type Gallery from '@/components/puck/Gallery';
import { GalleryConfig } from '@/components/puck/Gallery';
import type GridContainer from '@/components/puck/GridContainer';
import { GridContainerConfig } from '@/components/puck/GridContainer';
import type Heading from '@/components/puck/Heading';
import { HeadingConfig } from '@/components/puck/Heading';
import type Paragraph from '@/components/puck/Paragraph';
import { ParagraphConfig } from '@/components/puck/Paragraph';
import type Quote from '@/components/puck/Quote';
import { QuoteConfig } from '@/components/puck/Quote';
import type RichTextBlock from '@/components/puck/RichTextBlock';
import { RichTextBlockConfig } from '@/components/puck/RichTextBlock';
import type StatsGrid from '@/components/puck/StatsGrid';
import { StatsGridConfig } from '@/components/puck/StatsGrid';
import type Timeline from '@/components/puck/Timeline';
import { TimelineConfig } from '@/components/puck/Timeline';
import type VideoEmbed from '@/components/puck/VideoEmbed';
import { VideoEmbedConfig } from '@/components/puck/VideoEmbed';

export type ComponentProps = {
    Heading: React.ComponentProps<typeof Heading>;
    Paragraph: React.ComponentProps<typeof Paragraph>;
    Quote: React.ComponentProps<typeof Quote>;
    BlogImage: React.ComponentProps<typeof BlogImage>;
    CodeBlock: React.ComponentProps<typeof CodeBlock>;
    VideoEmbed: React.ComponentProps<typeof VideoEmbed>;
    GridContainer: React.ComponentProps<typeof GridContainer>;
    Divider: React.ComponentProps<typeof Divider>;
    CalloutBox: React.ComponentProps<typeof CalloutBox>;
    ButtonLink: React.ComponentProps<typeof ButtonLink>;
    RichTextBlock: React.ComponentProps<typeof RichTextBlock>;
    Gallery: React.ComponentProps<typeof Gallery>;
    Accordion: React.ComponentProps<typeof Accordion>;
    StatsGrid: React.ComponentProps<typeof StatsGrid>;
    Timeline: React.ComponentProps<typeof Timeline>;
};

const allComponents: Config['components'] = {
    Heading: HeadingConfig,
    Paragraph: ParagraphConfig,
    Quote: QuoteConfig,
    BlogImage: BlogImageConfig,
    CodeBlock: CodeBlockConfig,
    VideoEmbed: VideoEmbedConfig,
    GridContainer: GridContainerConfig,
    Divider: DividerConfig,
    CalloutBox: CalloutBoxConfig,
    ButtonLink: ButtonLinkConfig,
    RichTextBlock: RichTextBlockConfig,
    Gallery: GalleryConfig,
    Accordion: AccordionConfig,
    StatsGrid: StatsGridConfig,
    Timeline: TimelineConfig,
};

const rootConfig: Config['root'] = {
    fields: {},
    render: ({ children }: { children: React.ReactNode }) => (
        <div className="h-full w-full bg-background">
            <article className="mx-auto max-w-4xl px-4 py-10">
                {children}
            </article>
        </div>
    ),
};

export const puckConfig: Config = {
    root: rootConfig,
    components: allComponents,
};

export const blogPuckConfig: Config = {
    root: rootConfig,
    components: {
        Heading: HeadingConfig,
        Paragraph: ParagraphConfig,
        RichTextBlock: RichTextBlockConfig,
        Quote: QuoteConfig,
        BlogImage: BlogImageConfig,
        VideoEmbed: VideoEmbedConfig,
        CodeBlock: CodeBlockConfig,
        CalloutBox: CalloutBoxConfig,
        Divider: DividerConfig,
        ButtonLink: ButtonLinkConfig,
        GridContainer: GridContainerConfig,
    },
};

export const projectPuckConfig: Config = {
    root: rootConfig,
    components: {
        Heading: HeadingConfig,
        Paragraph: ParagraphConfig,
        RichTextBlock: RichTextBlockConfig,
        Gallery: GalleryConfig,
        VideoEmbed: VideoEmbedConfig,
        CodeBlock: CodeBlockConfig,
        StatsGrid: StatsGridConfig,
        Timeline: TimelineConfig,
        CalloutBox: CalloutBoxConfig,
        ButtonLink: ButtonLinkConfig,
        Divider: DividerConfig,
        GridContainer: GridContainerConfig,
    },
};

export const eventPuckConfig: Config = {
    root: rootConfig,
    components: {
        Heading: HeadingConfig,
        Paragraph: ParagraphConfig,
        RichTextBlock: RichTextBlockConfig,
        Timeline: TimelineConfig,
        StatsGrid: StatsGridConfig,
        Accordion: AccordionConfig,
        Gallery: GalleryConfig,
        VideoEmbed: VideoEmbedConfig,
        CalloutBox: CalloutBoxConfig,
        ButtonLink: ButtonLinkConfig,
        Divider: DividerConfig,
        GridContainer: GridContainerConfig,
    },
};

export const classroomPuckConfig: Config = {
    root: rootConfig,
    components: {
        Heading: HeadingConfig,
        Paragraph: ParagraphConfig,
        RichTextBlock: RichTextBlockConfig,
        VideoEmbed: VideoEmbedConfig,
        CodeBlock: CodeBlockConfig,
        Accordion: AccordionConfig,
        CalloutBox: CalloutBoxConfig,
        BlogImage: BlogImageConfig,
        ButtonLink: ButtonLinkConfig,
        Divider: DividerConfig,
        GridContainer: GridContainerConfig,
    },
};
