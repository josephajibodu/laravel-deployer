import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Activity, AlertCircle, ArrowLeft, Calendar, Edit, Server, TestTube, Trash2 } from 'lucide-react';
import { useState } from 'react';

interface Server {
    id: number;
    name: string;
    ip_address: string;
    status: string;
    created_at: string;
}

interface ServerProvider {
    id: number;
    name: string;
    provider_type: string;
    is_active: boolean;
    created_at: string;
    servers: Server[];
}

interface ServerProviderShowProps {
    serverProvider: ServerProvider;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Server Providers',
        href: '/server-providers',
    },
    {
        title: 'Details',
        href: '/server-providers/show',
    },
];

const providerTypeLabels: Record<string, string> = {
    digitalocean: 'DigitalOcean',
    aws: 'Amazon Web Services',
    linode: 'Linode',
    vultr: 'Vultr',
    hetzner: 'Hetzner',
    custom: 'Custom Provider',
};

const providerTypeColors: Record<string, string> = {
    digitalocean: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    aws: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
    linode: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    vultr: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
    hetzner: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    custom: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
};

const statusColors: Record<string, string> = {
    running: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    stopped: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    error: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
};

export default function ServerProviderShow({ serverProvider }: ServerProviderShowProps) {
    const [testingProvider, setTestingProvider] = useState(false);

    const handleTestConnection = () => {
        setTestingProvider(true);
        router.post(
            `/server-providers/${serverProvider.id}/test`,
            {},
            {
                onFinish: () => setTestingProvider(false),
            },
        );
    };

    const handleDelete = () => {
        if (confirm('Are you sure you want to delete this server provider?')) {
            router.delete(`/server-providers/${serverProvider.id}`);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${serverProvider.name} - Server Provider`} />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <Link href="/server-providers">
                            <Button variant="outline" size="sm">
                                <ArrowLeft className="mr-2 h-4 w-4" />
                                Back
                            </Button>
                        </Link>
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight">{serverProvider.name}</h1>
                            <p className="text-muted-foreground">Server provider details and associated servers.</p>
                        </div>
                    </div>
                    <div className="flex gap-2">
                        <Button variant="outline" onClick={handleTestConnection} disabled={testingProvider}>
                            <TestTube className="mr-2 h-4 w-4" />
                            {testingProvider ? 'Testing...' : 'Test Connection'}
                        </Button>
                        <Link href={`/server-providers/${serverProvider.id}/edit`}>
                            <Button variant="outline">
                                <Edit className="mr-2 h-4 w-4" />
                                Edit
                            </Button>
                        </Link>
                        <Button variant="outline" onClick={handleDelete} className="text-destructive hover:text-destructive">
                            <Trash2 className="mr-2 h-4 w-4" />
                            Delete
                        </Button>
                    </div>
                </div>

                <div className="grid gap-6 lg:grid-cols-3">
                    <div className="space-y-6 lg:col-span-2">
                        {/* Provider Information */}
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Server className="h-5 w-5" />
                                    Provider Information
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Name</label>
                                        <p className="text-sm">{serverProvider.name}</p>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Type</label>
                                        <div className="mt-1">
                                            <Badge
                                                variant="secondary"
                                                className={providerTypeColors[serverProvider.provider_type] || providerTypeColors.custom}
                                            >
                                                {providerTypeLabels[serverProvider.provider_type] || serverProvider.provider_type}
                                            </Badge>
                                        </div>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Status</label>
                                        <div className="mt-1">
                                            <Badge variant={serverProvider.is_active ? 'default' : 'secondary'}>
                                                {serverProvider.is_active ? 'Active' : 'Inactive'}
                                            </Badge>
                                        </div>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Created</label>
                                        <p className="flex items-center gap-1 text-sm">
                                            <Calendar className="h-3 w-3" />
                                            {new Date(serverProvider.created_at).toLocaleDateString()}
                                        </p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        {/* Associated Servers */}
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Activity className="h-5 w-5" />
                                    Associated Servers ({serverProvider.servers.length})
                                </CardTitle>
                                <CardDescription>Servers managed by this provider.</CardDescription>
                            </CardHeader>
                            <CardContent>
                                {serverProvider.servers.length === 0 ? (
                                    <div className="py-8 text-center">
                                        <Server className="mx-auto mb-4 h-12 w-12 text-muted-foreground" />
                                        <h3 className="mb-2 text-lg font-semibold">No servers</h3>
                                        <p className="mb-4 text-muted-foreground">This provider doesn't have any servers yet.</p>
                                        <Link href="/servers/create">
                                            <Button>Create Server</Button>
                                        </Link>
                                    </div>
                                ) : (
                                    <div className="space-y-3">
                                        {serverProvider.servers.map((server) => (
                                            <div key={server.id} className="flex items-center justify-between rounded-lg border p-3">
                                                <div className="flex items-center gap-3">
                                                    <Server className="h-4 w-4 text-muted-foreground" />
                                                    <div>
                                                        <p className="font-medium">{server.name}</p>
                                                        <p className="text-sm text-muted-foreground">{server.ip_address}</p>
                                                    </div>
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    <Badge variant="secondary" className={statusColors[server.status] || statusColors.pending}>
                                                        {server.status}
                                                    </Badge>
                                                    <Link href={`/servers/${server.id}`}>
                                                        <Button variant="outline" size="sm">
                                                            View
                                                        </Button>
                                                    </Link>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </div>

                    <div className="space-y-4">
                        <Alert>
                            <AlertCircle className="h-4 w-4" />
                            <AlertDescription>
                                This provider is {serverProvider.is_active ? 'active' : 'inactive'}.
                                {!serverProvider.is_active && ' Inactive providers cannot be used to create new servers.'}
                            </AlertDescription>
                        </Alert>

                        <Card>
                            <CardHeader>
                                <CardTitle>Quick Actions</CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-2">
                                <Link href={`/server-providers/${serverProvider.id}/edit`} className="block">
                                    <Button variant="outline" className="w-full justify-start">
                                        <Edit className="mr-2 h-4 w-4" />
                                        Edit Provider
                                    </Button>
                                </Link>
                                <Button variant="outline" className="w-full justify-start" onClick={handleTestConnection} disabled={testingProvider}>
                                    <TestTube className="mr-2 h-4 w-4" />
                                    {testingProvider ? 'Testing...' : 'Test Connection'}
                                </Button>
                                <Link href="/servers/create" className="block">
                                    <Button variant="outline" className="w-full justify-start">
                                        <Server className="mr-2 h-4 w-4" />
                                        Create Server
                                    </Button>
                                </Link>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
