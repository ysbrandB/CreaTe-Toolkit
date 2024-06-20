export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Item {
    id: number;
    title: string;
    description?: string;
    card_description?: string;
    wiring_instructions?: string;
    pros?: string;
    cons?: string;
    hardware_considerations?: string;
    software_considerations?: string;
    example_code?: string;
    public_id: string;
    photo_url?: string;
    wiring_photo_url?: string;
    attributes?: Attribute[];
    json_items?: string[];
}

export interface Attribute {
    id: number;
    title: string;
    description: string;
    attribute_type_id: number;
    attribute_type: AttributeType;
}

export interface AttributeType {
    id: number;
    title: string;
    description: string;
    color: string;
    attributes?: Attribute[];
}

interface Question {
    id: number,
    title: string,
    description: string,
    answers?: Answer[]
}
interface Answer {
    id?: number,
    text: string,
    attributes: Attribute[]
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};
