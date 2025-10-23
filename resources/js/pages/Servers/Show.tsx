import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { AlertCircle, Edit, MoreHorizontal, Plus, Server, TestTube, Trash2 } from 'lucide-react';
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
                    <div className="flex items-center space-x-4">
                        <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                            <span className="text-sm font-semibold text-blue-600 dark:text-blue-300">{server.name.charAt(0).toUpperCase()}</span>
                        </div>
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight">{server.name}</h1>
                            <div className="flex items-center space-x-2">
                                <Badge variant="secondary" className={statusColors[server.connection_status] || statusColors.pending}>
                                    {server.connection_status}
                                </Badge>
                            </div>
                        </div>
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
                        {/* Recent Sites Section */}
                        <Card>
                            <CardHeader className="flex flex-row items-center justify-between">
                                <CardTitle>Recent sites</CardTitle>
                                <Button variant="outline" size="sm">
                                    <Plus className="h-4 w-4" />
                                </Button>
                            </CardHeader>
                            <CardContent>
                                {server.sites.length > 0 ? (
                                    <div className="space-y-3">
                                        {server.sites.map((site, index) => (
                                            <div
                                                key={site.id}
                                                className="flex items-center justify-between border-b border-border pb-3 last:border-b-0"
                                            >
                                                <div className="flex items-center space-x-3">
                                                    <div className="flex h-8 w-8 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900">
                                                        <span className="text-xs font-semibold text-orange-600 dark:text-orange-300">
                                                            {site.name.charAt(0).toUpperCase()}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <p className="font-medium">{site.name}</p>
                                                        <p className="text-sm text-muted-foreground">
                                                            {site.repository} PHP {server.php_version}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div className="flex items-center space-x-2">
                                                    <span className="text-sm text-muted-foreground">
                                                        Deployed {index === 0 ? '5 hours ago' : index === 1 ? '1 week ago' : '1 month ago'}
                                                    </span>
                                                    <Button variant="ghost" size="sm">
                                                        <MoreHorizontal className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div className="py-8 text-center">
                                        <p className="text-muted-foreground">No sites deployed yet</p>
                                    </div>
                                )}
                            </CardContent>
                        </Card>

                        {/* Databases Section */}
                        <Card>
                            <CardHeader className="flex flex-row items-center justify-between">
                                <CardTitle>Databases</CardTitle>
                                <Button variant="outline" size="sm">
                                    <Plus className="h-4 w-4" />
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-2">
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm">flitbill</span>
                                        <Button variant="ghost" size="sm">
                                            <MoreHorizontal className="h-4 w-4" />
                                        </Button>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm">dhldb</span>
                                        <Button variant="ghost" size="sm">
                                            <MoreHorizontal className="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <div className="space-y-6">
                        {/* Details Section */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Details</CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div>
                                    <label className="text-sm font-medium text-muted-foreground">ID</label>
                                    <p className="text-sm">{server.id}</p>
                                </div>
                                <div>
                                    <label className="text-sm font-medium text-muted-foreground">Type</label>
                                    <p className="text-sm capitalize">{server.server_type}</p>
                                </div>
                                <div>
                                    <label className="text-sm font-medium text-muted-foreground">Database Type</label>
                                    <p className="text-sm">MySQL 8</p>
                                </div>
                                <div>
                                    <label className="text-sm font-medium text-muted-foreground">Region</label>
                                    <p className="text-sm">{server.region}</p>
                                </div>
                                <div>
                                    <label className="text-sm font-medium text-muted-foreground">PHP</label>
                                    <p className="text-sm">{server.php_version}</p>
                                </div>
                                <div>
                                    <label className="text-sm font-medium text-muted-foreground">Ubuntu</label>
                                    <p className="text-sm">{server.operating_system}</p>
                                </div>
                                <div>
                                    <label className="text-sm font-medium text-muted-foreground">Created</label>
                                    <p className="text-sm">
                                        {new Date(server.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                    </p>
                                </div>
                            </CardContent>
                        </Card>

                        {/* Networking Section */}
                        <Card>
                            <CardHeader>
                                <CardTitle>Networking</CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div>
                                    <label className="text-sm font-medium text-muted-foreground">Public IP</label>
                                    <p className="font-mono text-sm">{server.ip_address}</p>
                                </div>
                                {server.private_ip_address && (
                                    <div>
                                        <label className="text-sm font-medium text-muted-foreground">Private IP</label>
                                        <p className="font-mono text-sm">{server.private_ip_address}</p>
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
