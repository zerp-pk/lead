import { DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { useForm } from "@inertiajs/react";
import { useTranslation } from 'react-i18next';
import { Button } from "@/components/ui/button";
import { Label } from '@/components/ui/label';
import InputError from '@/components/ui/input-error';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { ScoreRule, ScoreField } from './types';

interface Props {
    rule?: ScoreRule | null;
    fields: ScoreField[];
    operators: Record<string, string>;
    sources: { id: number; name: string }[];
    onSuccess: () => void;
}

export default function RuleDialog({ rule, fields, operators, sources, onSuccess }: Props) {
    const { t } = useTranslation();
    const isEdit = !!rule;

    const { data, setData, post, put, processing, errors } = useForm({
        name: rule?.name ?? '',
        field: rule?.field ?? fields[0]?.key ?? 'email_present',
        operator: rule?.operator ?? 'is_set',
        value: rule?.value ?? '',
        points: rule?.points?.toString() ?? '10',
        is_active: rule?.is_active ?? true,
    });

    const fieldType = fields.find(f => f.key === data.field)?.type ?? 'bool';

    // Keep operator/value coherent with the selected field type.
    const onFieldChange = (key: string) => {
        const type = fields.find(f => f.key === key)?.type ?? 'bool';
        const nextOp = type === 'bool' ? 'is_set' : type === 'source' ? 'equals' : 'gte';
        setData({ ...data, field: key, operator: nextOp, value: '' });
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        const done = { onSuccess: () => onSuccess() };
        if (isEdit) put(route('lead.score-rules.update', rule!.id), done);
        else post(route('lead.score-rules.store'), done);
    };

    return (
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{isEdit ? t('Edit Scoring Rule') : t('Create Scoring Rule')}</DialogTitle>
            </DialogHeader>
            <form onSubmit={submit} className="space-y-4">
                <div>
                    <Label htmlFor="name">{t('Rule Name')}</Label>
                    <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} placeholder={t('e.g. Website lead')} required />
                    <InputError message={errors.name} />
                </div>

                <div>
                    <Label>{t('When')}</Label>
                    <Select value={data.field} onValueChange={onFieldChange}>
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            {fields.map(f => <SelectItem key={f.key} value={f.key}>{t(f.label)}</SelectItem>)}
                        </SelectContent>
                    </Select>
                    <InputError message={errors.field} />
                </div>

                {fieldType === 'numeric' && (
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <Label>{t('Operator')}</Label>
                            <Select value={data.operator} onValueChange={(v) => setData('operator', v)}>
                                <SelectTrigger><SelectValue /></SelectTrigger>
                                <SelectContent>
                                    {['gte', 'lte', 'equals'].map(op => <SelectItem key={op} value={op}>{t(operators[op])}</SelectItem>)}
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label>{t('Value')}</Label>
                            <Input type="number" value={data.value} onChange={(e) => setData('value', e.target.value)} required />
                            <InputError message={errors.value} />
                        </div>
                    </div>
                )}

                {fieldType === 'source' && (
                    <div>
                        <Label>{t('Source')}</Label>
                        <Select value={data.value} onValueChange={(v) => setData('value', v)}>
                            <SelectTrigger><SelectValue placeholder={t('Select source')} /></SelectTrigger>
                            <SelectContent>
                                {sources.map(s => <SelectItem key={s.id} value={s.id.toString()}>{s.name}</SelectItem>)}
                            </SelectContent>
                        </Select>
                        <InputError message={errors.value} />
                    </div>
                )}

                {fieldType === 'bool' && (
                    <p className="text-xs text-gray-500">{t('Points are awarded when this condition is true.')}</p>
                )}

                <div className="grid grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="points">{t('Points')}</Label>
                        <Input id="points" type="number" min="0" max="100" value={data.points} onChange={(e) => setData('points', e.target.value)} required />
                        <InputError message={errors.points} />
                    </div>
                    <div>
                        <Label>{t('Status')}</Label>
                        <Select value={data.is_active ? '1' : '0'} onValueChange={(v) => setData('is_active', v === '1')}>
                            <SelectTrigger><SelectValue /></SelectTrigger>
                            <SelectContent>
                                <SelectItem value="1">{t('Active')}</SelectItem>
                                <SelectItem value="0">{t('Inactive')}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <div className="flex justify-end gap-2">
                    <Button type="button" variant="outline" onClick={() => onSuccess()}>{t('Cancel')}</Button>
                    <Button type="submit" disabled={processing}>{isEdit ? t('Update') : t('Create')}</Button>
                </div>
            </form>
        </DialogContent>
    );
}
