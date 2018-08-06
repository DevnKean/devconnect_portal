<?php
/**
 * Created by PhpStorm.
 * User: Lixing
 * Date: 9/9/17
 * Time: 10:23 PM
 */

namespace ThemeBundle\EventListener;

use AppBundle\Entity\Lead;
use AppBundle\Entity\PotentialSupplier;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use ThemeBundle\Event\SidebarMenuEvent;
use ThemeBundle\Model\MenuItemModel;

class MenuItemListListener
{

    /**
     * @var object|AuthorizationChecker
     */
    protected $checker;

    /**
     * @var User
     */
    protected $user = null;

    /**
     * @var \Doctrine\ORM\EntityManager|object
     */
    protected $manager;

    /**
     * MenuItemListListener constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        if ($container->get('security.token_storage')->getToken()) {
            $this->user = $container->get('security.token_storage')->getToken()->getUser();
        }

        $this->checker = $container->get('security.authorization_checker');

        $this->manager = $container->get('doctrine.orm.entity_manager');
    }


    public function onSetupMenu(SidebarMenuEvent $event)
    {

        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }

    }

    protected function getMenu(Request $request)
    {
        // Build your menu here by constructing a MenuItemModel array

        if ($this->checker->isGranted('ROLE_SUPER_ADMIN')) {
            $newLeads = $this->manager->getRepository('AppBundle:Lead')->findBy(['status' => [Lead::STATUS_PENDING, Lead::STATUS_CONTACT_REQUIRED]]);
            $activeLeads = $this->manager->getRepository('AppBundle:Lead')->findBy(['status' => Lead::STATUS_APPROVE]);
            $newPotentialSuppliers = $this->manager->getRepository('AppBundle:PotentialSupplier')->findBy(['status' => PotentialSupplier::STATUS_POTENTIAL]);
            $menuItems = array(
                $dashboard = new MenuItemModel('dashboard', 'Dashboard', 'admin_dashboard', [], 'fa fa-dashboard'),
                $users = new MenuItemModel('users', 'Users', 'user_all', [], 'fa fa-user'),
                $suppliers = new MenuItemModel(
                    'suppliers',
                    'Suppliers',
                    'supplier_all',
                    array(/* options */),
                    'fa fa-building'
                ),
                $potentialSuppliers = new MenuItemModel(
                    'potential-suppliers',
                    'Potential Suppliers',
                    '',
                    array(/* options */),
                    'fa fa-building'
                ),
                $clients = new MenuItemModel(
                    'clients',
                    'Clients',
                    'client_all',
                    array(/* options */),
                    'fa fa-building'
                ),
                $contracts = new MenuItemModel(
                    'contracts',
                    'Contracts',
                    'contract_all',
                    array(/* options */),
                    'fa fa-file-text'
                ),
                $leads = new MenuItemModel('leads', 'Leads', '', array(/* options */), 'fa fa-comments'),
                $campaigns = new MenuItemModel(
                    'campaigns',
                    'Active Campaigns',
                    'campaign_all',
                    array(/* options */),
                    'fa fa-commenting'
                ),
                $invoices = new MenuItemModel(
                    'supplier-invoices', 'Supplier Invoices', 'supplier_invoices_all', array(/* options */), 'fa fa-money'
                ),
                $invoices = new MenuItemModel(
                    'invoices', 'CX Connect Invoices', 'invoice_all', array(/* options */), 'fa fa-money'
                ),
                $payments = new MenuItemModel(
                    'payments', 'Payments', 'payment_all', array(/* options */), 'fa fa-money'
                ),
                $commissions = new MenuItemModel(
                    'commissions', 'Commission Models', 'commission_all', array(/* options */), 'fa fa-money'
                ),
                $logs = new MenuItemModel('change-logs', 'Change Logs', 'logs', [], 'fa fa-tint')
            );
            $potentialSuppliers->addChild(new MenuItemModel('new-potential-suppliers', 'New Potential Supplier', 'potential_supplier_all', [], 'fa fa-building', count($newPotentialSuppliers), 'green'));
            $potentialSuppliers->addChild(new MenuItemModel('actioned-potential-suppliers', 'Actioned Potential Supplier', 'potential_supplier_actioned', [], 'fa fa-building-o'));
            $potentialSuppliers->addChild(new MenuItemModel('deleted-potential-suppliers', 'Deleted Potential Supplier', 'potential_supplier_deleted', [], 'fa fa-building'));
            $leads->addChild(new MenuItemModel('new-leads', 'New Leads', 'leads_pending', [], 'fa fa-comment-o', count($newLeads), 'yellow'));
            $leads->addChild(new MenuItemModel('manage-leads', 'Manage Leads', 'leads_active', [], 'fa fa-comment', count($activeLeads), 'green'));
            $leads->addChild(new MenuItemModel(
                'expired-leads',
                'Lost Leads',
                'leads_expired',
                array(/* options */),
                'fa fa-comments-o'
            ));
        } else {
            $menuItems[] = new MenuItemModel(
                'dashboard',
                'Dashboard',
                'supplier_dashboard',
                [],
                'fa fa-dashboard'
            );

            if ($this->checker->isGranted('ROLE_ADMIN')) {
                $menuItems[] = new MenuItemModel('users', 'Users', 'supplier_users_all', [], 'fa fa-user');
            }

            $menuItems[] = $leads = new MenuItemModel(
                'leads',
                'Leads',
                '',
                array(/* options */),
                'fa fa-commenting'
            );
            $expiredLeadCount = $this->manager->getRepository('AppBundle:LeadSupplier')->getExpiredLeadsCount($this->user->getSupplier());
            $activeLeadCount = $this->manager->getRepository('AppBundle:LeadSupplier')->getActiveLeadsCount($this->user->getSupplier());
            $newLeadCount = $this->manager->getRepository('AppBundle:LeadSupplier')->getNewLeadsCount($this->user->getSupplier());
            $leads->addChild(new MenuItemModel('new-leads', 'New Leads', 'supplier_leads_new', [], 'fa fa-comment-o', $newLeadCount, 'yellow'));
            $leads->addChild(new MenuItemModel('manage-leads', 'Manage Leads', 'supplier_leads_manage', [], 'fa fa-comment', $activeLeadCount, 'green'));
            $leads->addChild(new MenuItemModel(
                'expired-leads',
                'Lost Leads',
                'supplier_leads_expired',
                array(/* options */),
                'fa fa-comments-o',
                  $expiredLeadCount,
                'red'
            ));
            $leads->addChild(new MenuItemModel(
                'archived-leads',
                'Archived Leads',
                'supplier_leads_archived',
                array(/* options */),
                'fa fa-comments-o'
            ));
            $menuItems[] = new MenuItemModel(
                'campaigns',
                'Active Campaigns',
                'campaign_view',
                array(/* options */),
                'fa fa-commenting'
            );
            $menuItems[] = new MenuItemModel(
                'information',
                'Business Information',
                'profile_information',
                array(/* options */),
                'fa fa-commenting'
            );
            // Add some children

            // A child with an icon
            if ($this->checker->isGranted('ROLE_ADMIN')) {
                $contract = $this->user->getSupplier()->getContract();
                if ($contract) {
                    foreach ($contract->getContractServices() as $contractService) {
                        $menu = new MenuItemModel('profile', 'Profile', '', [], 'fa fa-bell');
                        foreach ($contractService->getService()->getProfiles() as $profile) {
                            if ($supplierProfile = $profile->getSupplierProfile($this->user->getSupplier())) {
                                $badge = $supplierProfile->getBadge();
                                $menu->addChild(
                                    new MenuItemModel(
                                        $profile->getName(),
                                        $profile->getName(),
                                        $profile->getRoute(),
                                        [],
                                        'fa ' . $profile->getIcon(),
                                        $badge['status'],
                                        $badge['color']
                                    )
                                );
                            }
                        }
                        if ($menu->hasChildren()) {
                            $menuItems[] = $menu;
                        }
                    }
                }

                $menuItems[] = new MenuItemModel('log', 'Change logs', 'log_all', [], 'fa fa-bell');
            }

        }

        // A child with default circle icon
        return $this->activateByRoute($request->get('_route'), $menuItems);
    }

    /**
     * @param                 $route
     * @param MenuItemModel[] $items
     *
     * @return mixed
     */
    protected function activateByRoute($route, $items)
    {

        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } else {
                if ($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }

        return $items;
    }
}