import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { AlertCircle, Edit, Server, TestTube, Trash2 } from 'lucide-react';
import { useState } from 'react';

interface ServerProvider {
    id: number;
    name: string;
    provider_type: string;
}

interface Site {
    id: number;
    name: string;
    repository: string;
    deployment_status: string;
}

interface Server {
    id: number;
    name: string;
    ip_address: string;
    private_ip_address?: string;
    server_type: string;
    region: string;
    operating_system: string;
    php_version: string;
    connection_status: string;
    connection_status_updated_at?: string;
    connection_status_output?: string;
    provisioned: boolean;
    created_at: string;
    updated_at: string;
    server_provider: ServerProvider;
    sites: Site[];
}

interface ServersShowProps {
    server: Server;
}

const statusColors: Record<string, string> = {
    connected: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    disconnected: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    provisioning: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    error: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
};

const serverTypeColors: Record<string, string> = {
    app: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    database: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
    cache: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
    worker: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    load_balancer: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
};

export default function Show({ server }: ServersShowProps) {
    const [testingConnection, setTestingConnection] = useState(false);

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Servers',
            href: '/servers',
        },
        {
            title: server.name,
            href: `/servers/${server.id}`,
        },
    ];

    const handleTestConnection = () => {
        setTestingConnection(true);
        router.post(
            `/servers/${server.id}/test`,
            {},
            {
                onFinish: () => setTestingConnection(false),
            },
        );
    };

    const handleDelete = () => {
        if (confirm('Are you sure you want to delete this server? This action cannot be undone.')) {
            router.delete(`/servers/${server.id}`);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Server: ${server.name}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">{server.name}</h1>
                        <p className="text-muted-foreground">Server details and management</p>
                    </div>
                    <div className="flex gap-2">
                        <Button variant="outline" onClick={handleTestConnection} disabled={testingConnection}>
                            <TestTube className="mr-2 h-4 w-4" />
                            {testingConnection ? 'Testing...' : 'Test Connection'}
                        </Button>
                        <Link href={`/servers/${server.id}/edit`}>
                            <Button variant="outline">
                                <Edit className="mr-2 h-4 w-4" /> Edit
                            </Button>
                        </Link>
                        <Button variant="destructive" onClick={handleDelete}>
                            <Trash2 className="mr-2 h-4 w-4" /> Delete
                        </Button>
                    </div>
                </div>

                {server.connection_status === 'error' && server.connection_status_output && (
                    <Alert variant="destructive">
                        <AlertCircle className="h-4 w-4" />
                        <AlertDescription>
                            <strong>Connection Error:</strong> {server.connection_status_output}
                        </AlertDescription>
                    </Alert>
                )}

                <div className="grid gap-6 lg:grid-cols-3">
                    <div className="space-y-6 lg:col-span-2">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Server className="h-5 w-5" />
                                    Server Information
                                </CardTitle>
                                <CardDescription>Basic server configuration and status</CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Name</label>
                                        <p className="text-sm">{server.name}</p>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Type</label>
                                        <div className="mt-1">
                                            <Badge variant="secondary" className={serverTypeColors[server.server_type] || serverTypeColors.app}>
                                                {server.server_type}
                                            </Badge>
                                        </div>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">IP Address</label>
                                        <p className="font-mono text-sm">{server.ip_address}</p>
                                    </div>
                                    {server.private_ip_address && (
                                        <div>
                                            <label className="text-sm font-medium text-muted-foreground">Private IP</label>
                                            <p className="font-mono text-sm">{server.private_ip_address}</p>
                                        </div>
                                    )}
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Region</label>
                                        <p className="text-sm">{server.region}</p>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Operating System</label>
                                        <p className="text-sm">{server.operating_system}</p>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">PHP Version</label>
                                        <p className="text-sm">{server.php_version}</p>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Status</label>
                                        <div className="mt-1">
                                            <Badge variant="secondary" className={statusColors[server.connection_status] || statusColors.pending}>
                                                {server.connection_status}
                                            </Badge>
                                        </div>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Provisioned</label>
                                        <div className="mt-1">
                                            <Badge variant={server.provisioned ? 'default' : 'secondary'}>{server.provisioned ? 'Yes' : 'No'}</Badge>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>Provider Information</CardTitle>
                                <CardDescription>Server provider details</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Provider</label>
                                        <p className="text-sm">{server.server_provider.name}</p>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Provider Type</label>
                                        <p className="text-sm capitalize">{server.server_provider.provider_type}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        {server.sites.length > 0 && (
                            <Card>
                                <CardHeader>
                                    <CardTitle>Sites ({server.sites.length})</CardTitle>
                                    <CardDescription>Sites deployed on this server</CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <div className="space-y-2">
                                        {server.sites.map((site) => (
                                            <div key={site.id} className="flex items-center justify-between rounded-lg border p-3">
                                                <div>
                                                    <p className="font-medium">{site.name}</p>
                                                    <p className="text-sm text-muted-foreground">{site.repository}</p>
                                                </div>
                                                <Badge variant="outline">{site.deployment_status}</Badge>
                                            </div>
                                        ))}
                                    </div>
                                </CardContent>
                            </Card>
                        )}
                    </div>

                    <div className="space-y-6">
                        <Card>
                            <CardHeader>
                                <CardTitle>Quick Actions</CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-2">
                                <Button variant="outline" className="w-full justify-start">
                                    <Server className="mr-2 h-4 w-4" />
                                    View Logs
                                </Button>
                                <Button variant="outline" className="w-full justify-start">
                                    <Server className="mr-2 h-4 w-4" />
                                    Restart Server
                                </Button>
                                <Button variant="outline" className="w-full justify-start">
                                    <Server className="mr-2 h-4 w-4" />
                                    SSH Access
                                </Button>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>Server Stats</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-4">
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Created</label>
                                        <p className="text-sm">{new Date(server.created_at).toLocaleDateString()}</p>
                                    </div>
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Last Updated</label>
                                        <p className="text-sm">{new Date(server.updated_at).toLocaleDateString()}</p>
                                    </div>
                                    {server.connection_status_updated_at && (
                                        <div>
                                            <label className="text-sm font-medium text-muted-foreground">Last Status Check</label>
                                            <p className="text-sm">{new Date(server.connection_status_updated_at).toLocaleDateString()}</p>
                                        </div>
                                    )}
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
