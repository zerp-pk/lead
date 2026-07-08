import { useState } from 'react';
import { Head, usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { useDeleteHandler } from '@/hooks/useDeleteHandler';
import AuthenticatedLayout from "@/layouts/authenticated-layout";
import { Button } from '@/components/ui/button';
import { Card, CardContent } from "@/components/ui/card";
import { DataTable } from "@/components/ui/data-table";
import { Dialog } from "@/components/ui/dialog";
import { ConfirmationDialog } from '@/components/ui/confirmation-dialog';
import { Plus, Edit, Trash2, Target } from "lucide-react";
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from "@/components/ui/tooltip";
import { Badge } from "@/components/ui/badge";

import RuleDialog from './RuleDialog';
import NoRecordsFound from '@/components/no-records-found';
import { ScoreRule, LeadScoringIndexProps } from './types';
import SystemSetupSidebar from "../SystemSetupSidebar";

export default function Index() {
    const { t } = useTranslation();
    const { rules, fields, operators, sources, auth } = usePage<LeadScoringIndexProps>().props;

    const [dialog, setDialog] = useState<{ open: boolean; rule: ScoreRule | null }>({ open: false, rule: null });

    const { deleteState, openDeleteDialog, closeDeleteDialog, confirmDelete } = useDeleteHandler({
        routeName: 'lead.score-rules.destroy',
        defaultMessage: t('Are you sure you want to delete this scoring rule?')
    });

    const fieldLabel = (key: string) => t(fields.find(f => f.key === key)?.label ?? key);
    const valueLabel = (rule: ScoreRule) => {
        if (fields.find(f => f.key === rule.field)?.type === 'source') {
            return sources.find(s => s.id.toString() === rule.value)?.name ?? rule.value;
        }
        return rule.value;
    };
    const conditionText = (rule: ScoreRule) => {
        const parts = [fieldLabel(rule.field), t(operators[rule.operator] ?? rule.operator)];
        if (rule.operator !== 'is_set' && rule.value) parts.push(String(valueLabel(rule)));
        return parts.join(' ');
    };

    const canManage = auth.user?.permissions?.some((p: string) => ['edit-leads', 'delete-leads'].includes(p));

    const columns = [
        { key: 'name', header: t('Rule') },
        { key: 'condition', header: t('Condition'), render: (_: any, r: ScoreRule) => <span className="text-sm text-gray-600">{conditionText(r)}</span> },
        { key: 'points', header: t('Points'), render: (_: any, r: ScoreRule) => <Badge variant="secondary">+{r.points}</Badge> },
        {
            key: 'is_active', header: t('Status'),
            render: (_: any, r: ScoreRule) => (
                <Badge className={r.is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600'}>
                    {r.is_active ? t('Active') : t('Inactive')}
                </Badge>
            )
        },
        ...(canManage ? [{
            key: 'actions', header: t('Action'),
            render: (_: any, rule: ScoreRule) => (
                <div className="flex gap-1">
                    <TooltipProvider>
                        {auth.user?.permissions?.includes('edit-leads') && (
                            <Tooltip delayDuration={0}>
                                <TooltipTrigger asChild>
                                    <Button variant="ghost" size="sm" onClick={() => setDialog({ open: true, rule })} className="h-8 w-8 p-0 text-blue-600 hover:text-blue-700">
                                        <Edit className="h-4 w-4" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent><p>{t('Edit')}</p></TooltipContent>
                            </Tooltip>
                        )}
                        {auth.user?.permissions?.includes('delete-leads') && (
                            <Tooltip delayDuration={0}>
                                <TooltipTrigger asChild>
                                    <Button variant="ghost" size="sm" onClick={() => openDeleteDialog(rule.id)} className="h-8 w-8 p-0 text-red-600 hover:text-red-700">
                                        <Trash2 className="h-4 w-4" />
                                    </Button>
                                </TooltipTrigger>
                                <TooltipContent><p>{t('Delete')}</p></TooltipContent>
                            </Tooltip>
                        )}
                    </TooltipProvider>
                </div>
            )
        }] : [])
    ];

    return (
        <TooltipProvider>
            <AuthenticatedLayout
                breadcrumbs={[
                    { label: t('CRM'), url: route('lead.leads.index') },
                    { label: t('System Setup') },
                    { label: t('Lead Scoring') }
                ]}
                pageTitle={t('System Setup')}
            >
                <Head title={t('Lead Scoring')} />

                <div className="flex flex-col md:flex-row gap-8">
                    <div className="md:w-64 flex-shrink-0">
                        <SystemSetupSidebar activeItem="lead-scoring" />
                    </div>

                    <div className="flex-1">
                        <Card className="shadow-sm">
                            <CardContent className="p-6">
                                <div className="flex justify-between items-center mb-2">
                                    <h3 className="text-lg font-medium">{t('Lead Scoring Rules')}</h3>
                                    {auth.user?.permissions?.includes('create-leads') && (
                                        <Tooltip delayDuration={0}>
                                            <TooltipTrigger asChild>
                                                <Button size="sm" onClick={() => setDialog({ open: true, rule: null })}>
                                                    <Plus className="h-4 w-4" />
                                                </Button>
                                            </TooltipTrigger>
                                            <TooltipContent><p>{t('Create')}</p></TooltipContent>
                                        </Tooltip>
                                    )}
                                </div>
                                <p className="text-xs text-gray-500 mb-6">{t('Each matching rule adds its points; scores are normalized to 0–100 and shown as Hot / Warm / Cold on leads.')}</p>
                                <div className="overflow-y-auto max-h-[75vh] w-full">
                                    <div className="min-w-[600px]">
                                        <DataTable
                                            data={rules}
                                            columns={columns}
                                            emptyState={
                                                <NoRecordsFound
                                                    icon={Target}
                                                    title={t('No scoring rules yet')}
                                                    description={t('Add rules to automatically score and prioritize your leads.')}
                                                    createPermission="create-leads"
                                                    onCreateClick={() => setDialog({ open: true, rule: null })}
                                                    createButtonText={t('Create Rule')}
                                                    className="h-auto"
                                                />
                                            }
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>

                <Dialog open={dialog.open} onOpenChange={(open) => setDialog({ open, rule: open ? dialog.rule : null })}>
                    {dialog.open && (
                        <RuleDialog
                            rule={dialog.rule}
                            fields={fields}
                            operators={operators}
                            sources={sources}
                            onSuccess={() => setDialog({ open: false, rule: null })}
                        />
                    )}
                </Dialog>

                <ConfirmationDialog
                    open={deleteState.isOpen}
                    onOpenChange={closeDeleteDialog}
                    title={t('Delete Scoring Rule')}
                    message={deleteState.message}
                    confirmText={t('Delete')}
                    onConfirm={confirmDelete}
                    variant="destructive"
                />
            </AuthenticatedLayout>
        </TooltipProvider>
    );
}
