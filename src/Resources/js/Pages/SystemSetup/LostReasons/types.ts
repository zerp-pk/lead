import { AuthContext } from '@/types/common';

export interface LostReason {
    id: number;
    name: string;
    created_at: string;
}

export interface LostReasonFormData {
    name: string;
}

export interface CreateLostReasonProps {
    onSuccess: () => void;
}

export interface EditLostReasonProps {
    lostReason: LostReason;
    onSuccess: () => void;
}

export interface LostReasonModalState {
    isOpen: boolean;
    mode: 'add' | 'edit' | '';
    data: LostReason | null;
}

export interface LostReasonsIndexProps {
    lostReasons: LostReason[];
    auth: AuthContext;
    [key: string]: unknown;
}
