import { Head, usePage, router } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import AuthenticatedLayout from "@/layouts/authenticated-layout";
import { Card, CardContent } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Phone, Mail, Users, CheckSquare, Calendar, AlertCircle } from "lucide-react";
import { formatDate } from '@/utils/helpers';
import { ACTIVITY_TYPES } from '../../utils/activities.mjs';

interface Activity {
    id: number;
    type: string;
    name: string;
    priority?: string;
    date: string | null;
    time: string | null;
    record_type: 'lead' | 'deal';
    record_id: number;
    record_name: string;
    url: string;
    state: 'overdue' | 'today' | 'planned';
}

const TYPE_ICON: Record<string, any> = { todo: CheckSquare, call: Phone, email: Mail, meeting: Users };

const GROUPS: { key: Activity['state']; label: string; dot: string; text: string }[] = [
    { key: 'overdue', label: 'Overdue', dot: 'bg-red-500', text: 'text-red-600' },
    { key: 'today', label: 'Today', dot: 'bg-amber-500', text: 'text-amber-600' },
    { key: 'planned', label: 'Planned', dot: 'bg-green-500', text: 'text-green-600' },
];

export default function Index() {
    const { t } = useTranslation();
    const { activities, counts } = usePage<{ activities: Activity[]; counts: Record<string, number> }>().props;

    return (
        <AuthenticatedLayout
            breadcrumbs={[{ label: t('CRM'), url: route('lead.index') }, { label: t('My Activities') }]}
            pageTitle={t('My Activities')}
        >
            <Head title={t('My Activities')} />

            <div className="space-y-6">
                {activities.length === 0 && (
                    <Card><CardContent className="p-10 text-center text-gray-400">{t('No open activities. You are all caught up.')}</CardContent></Card>
                )}

                {GROUPS.map(group => {
                    const items = activities.filter(a => a.state === group.key);
                    if (items.length === 0) return null;
                    return (
                        <div key={group.key}>
                            <div className="flex items-center gap-2 mb-2">
                                <span className={`h-2.5 w-2.5 rounded-full ${group.dot}`} />
                                <h3 className={`text-sm font-semibold ${group.text}`}>{t(group.label)}</h3>
                                <Badge variant="secondary">{counts[group.key] ?? items.length}</Badge>
                            </div>
                            <Card>
                                <CardContent className="p-0 divide-y">
                                    {items.map(a => {
                                        const Icon = TYPE_ICON[a.type] || CheckSquare;
                                        return (
                                            <button
                                                key={`${a.record_type}-${a.id}`}
                                                onClick={() => router.get(a.url)}
                                                className="w-full flex items-center gap-3 p-3 text-left hover:bg-gray-50 transition-colors"
                                            >
                                                <Icon className="h-4 w-4 text-gray-500 shrink-0" />
                                                <div className="flex-1 min-w-0">
                                                    <p className="text-sm font-medium text-gray-900 truncate">{a.name}</p>
                                                    <p className="text-xs text-gray-500 truncate">
                                                        {ACTIVITY_TYPES[a.type] || a.type} · {a.record_name}
                                                        <span className="ml-1 uppercase text-[10px] text-gray-400">{a.record_type}</span>
                                                    </p>
                                                </div>
                                                <div className="flex items-center gap-1 text-xs text-gray-500 shrink-0">
                                                    {a.state === 'overdue' && <AlertCircle className="h-3 w-3 text-red-500" />}
                                                    <Calendar className="h-3 w-3" />
                                                    {a.date ? formatDate(a.date) : t('No date')}
                                                    {a.time && <span className="text-gray-400">{a.time}</span>}
                                                </div>
                                            </button>
                                        );
                                    })}
                                </CardContent>
                            </Card>
                        </div>
                    );
                })}
            </div>
        </AuthenticatedLayout>
    );
}
