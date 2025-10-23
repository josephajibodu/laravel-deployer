import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { AlertCircle, Plus, Server, TestTube } from 'lucide-react';
import { useState } from 'react';

interface ServerProvider {
    id: number;
    name: string;
    provider_type: string;
    is_active: boolean;
    servers_count: number;
    created_at: string;
}

interface ServerProvidersIndexProps {
    serverProviders: ServerProvider[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Server Providers',
        href: '/server-providers',
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

export default function ServerProvidersIndex({ serverProviders }: ServerProvidersIndexProps) {
    const [testingProvider, setTestingProvider] = useState<number | null>(null);

    const handleTestConnection = (providerId: number) => {
        setTestingProvider(providerId);
        router.post(
            `/server-providers/${providerId}/test`,
            {},
            {
                onFinish: () => setTestingProvider(null),
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Server Providers" />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Server Providers</h1>
                        <p className="text-muted-foreground">Manage your cloud server providers and their credentials.</p>
                    </div>
                    <Link href="/server-providers/create">
                        <Button>
                            <Plus className="mr-2 h-4 w-4" />
                            Add Provider
                        </Button>
                    </Link>
                </div>

                {serverProviders.length === 0 ? (
                    <Card>
                        <CardContent className="flex flex-col items-center justify-center py-16">
                            <Server className="mb-4 h-12 w-12 text-muted-foreground" />
                            <h3 className="mb-2 text-lg font-semibold">No server providers</h3>
                            <p className="mb-6 max-w-sm text-center text-muted-foreground">
                                Get started by adding your first server provider. Connect to DigitalOcean, AWS, Linode, or other cloud providers.
                            </p>
                            <Link href="/server-providers/create">
                                <Button>
                                    <Plus className="mr-2 h-4 w-4" />
                                    Add Your First Provider
                                </Button>
                            </Link>
                        </CardContent>
                    </Card>
                ) : (
                    <div className="flex flex-col gap-4">
                        {serverProviders.map((provider) => (
                            <Link key={provider.id} href={`/server-providers/${provider.id}`}>
                                <Card className="cursor-pointer p-0 transition-colors hover:bg-muted/50">
                                    <CardContent className="p-3">
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center space-x-4">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900">
                                                    <span className="text-sm font-semibold text-blue-600 dark:text-blue-300">
                                                        {provider.name.charAt(0).toUpperCase()}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h3 className="text-lg font-semibold">{provider.name}</h3>
                                                    <p className="text-sm text-muted-foreground">
                                                        {providerTypeLabels[provider.provider_type] || provider.provider_type} ·{' '}
                                                        {provider.servers_count} servers · Added {new Date(provider.created_at).toLocaleDateString()}
                                                    </p>
                                                </div>
                                            </div>
                                            <div className="flex items-center space-x-4">
                                                <div className="text-right text-sm text-muted-foreground">
                                                    <Badge variant={provider.is_active ? 'default' : 'secondary'}>
                                                        {provider.is_active ? 'Active' : 'Inactive'}
                                                    </Badge>
                                                </div>
                                                <div className="flex items-center space-x-2">
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                        onClick={(e) => {
                                                            e.preventDefault();
                                                            e.stopPropagation();
                                                            handleTestConnection(provider.id);
                                                        }}
                                                        disabled={testingProvider === provider.id}
                                                    >
                                                        <TestTube className="mr-2 h-4 w-4" />
                                                        {testingProvider === provider.id ? 'Testing...' : 'Test'}
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

                {serverProviders.length > 0 && (
                    <Alert>
                        <AlertCircle className="h-4 w-4" />
                        <AlertDescription>
                            Server providers store your cloud credentials securely. Make sure to test connections after adding new providers.
                        </AlertDescription>
                    </Alert>
                )}
            </div>
        </AppLayout>
    );
}
