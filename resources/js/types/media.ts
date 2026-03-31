export type DropzoneFile = File & {
    preview: string;
};

export type ResponsiveImages = {
    xl?: string;
    lg?: string;
    base?: string;
    md?: string;
    sm?: string;
    xs?: string;
};

export type ImageDimensions = {
    width: number;
    height: number;
};

export type Media = {
    id: number;
    urls: {
        [key: string]: string;
        original: string;
    };
    dimensions: {
        [key: string]: ImageDimensions;
    };
    responsive: ResponsiveImages;
    is_processed: boolean;
    custom_properties: string[];
};
