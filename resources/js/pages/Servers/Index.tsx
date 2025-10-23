import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Plus, Server, TestTube } from 'lucide-react';
import { useState } from 'react';

interface ServerProvider {
    id: number;
    name: string;
    provider_type: string;
}

interface Server {
    id: number;
    name: string;
    server_type: string;
    region: string;
    connection_status: string;
    provisioned: boolean;
    created_at: string;
    server_provider: ServerProvider;
}

interface ServersIndexProps {
    servers: Server[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Servers',
        href: '/servers',
    },
];

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

export default function Index({ servers }: ServersIndexProps) {
    const [testingServer, setTestingServer] = useState<number | null>(null);

    const handleTestConnection = (serverId: number) => {
        setTestingServer(serverId);
        router.post(
            `/servers/${serverId}/test`,
            {},
            {
                onFinish: () => setTestingServer(null),
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Servers" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Servers</h1>
                        <p className="text-muted-foreground">Manage your deployed servers and infrastructure.</p>
                    </div>
                    <Link href="/servers/create">
                        <Button>
                            <Plus className="mr-2 h-4 w-4" /> Create Server
                        </Button>
                    </Link>
                </div>

                {servers.length === 0 ? (
                    <Card>
                        <CardContent className="flex flex-col items-center justify-center py-16">
                            <Server className="mb-4 h-12 w-12 text-muted-foreground" />
                            <h3 className="mb-2 text-lg font-semibold">No servers</h3>
                            <p className="mb-6 max-w-sm text-center text-muted-foreground">
                                Get started by creating your first server. Deploy your applications with ease.
                            </p>
                            <Link href="/servers/create">
                                <Button>Create Server</Button>
                            </Link>
                        </CardContent>
                    </Card>
                ) : (
                    <div className="flex flex-col gap-4">
                        {servers.map((server) => (
                            <Link key={server.id} href={`/servers/${server.id}`}>
                                <Card className="cursor-pointer p-0 transition-colors hover:bg-muted/50">
                                    <CardContent className="p-3">
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center space-x-4">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                                    <span className="text-sm font-semibold text-blue-600 dark:text-blue-300">
                                                        {server.name.charAt(0).toUpperCase()}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h3 className="text-lg font-semibold">{server.name}</h3>
                                                    <p className="text-sm text-muted-foreground">
                                                        {server.ip_address} · {server.server_type} · PHP {server.php_version} · MySQL 8
                                                    </p>
                                                </div>
                                            </div>
                                            <div className="flex items-center space-x-4">
                                                <div className="text-right text-sm text-muted-foreground">
                                                    <p>{server.sites?.length || 0} sites · 0 background process · 0 scheduled jobs</p>
                                                </div>
                                                <div className="flex items-center space-x-2">
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                        onClick={(e) => {
                                                            e.preventDefault();
                                                            e.stopPropagation();
                                                            handleTestConnection(server.id);
                                                        }}
                                                        disabled={testingServer === server.id}
                                                    >
                                                        <TestTube className="mr-2 h-4 w-4" />
                                                        {testingServer === server.id ? 'Testing...' : 'Test'}
                                                    </Button>
                                                </div>
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
