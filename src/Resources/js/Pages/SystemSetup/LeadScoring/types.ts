import { AuthContext } from '@/types/common';

export interface ScoreRule {
    id: number;
    name: string;
    field: string;
    operator: string;
    value: string | null;
    points: number;
    is_active: boolean;
}

export interface ScoreField {
    key: string;
    label: string;
    type: 'bool' | 'numeric' | 'source';
}

export interface LeadScoringIndexProps {
    rules: ScoreRule[];
    fields: ScoreField[];
    operators: Record<string, string>;
    sources: { id: number; name: string }[];
    auth: AuthContext;
    [key: string]: unknown;
}
