<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Job\CountJob;
use App\Job\ModelJob;
use App\Job\RetryJob;
use App\Model\User;
use App\Service\QueueService;
use Hyperf\Constants\ConstantsCollector;
use Hyperf\Di\Annotation\AspectCollector;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class QueueController extends Controller
{
    /**
     * @Inject
     * @var QueueService
     */
    protected $service;

    public function index()
    {
        $this->service->push([
            'group@hyperf.io',
            'https://doc.hyperf.io',
            'https://www.hyperf.io',
        ]);

        return 'success';
    }

    public function model()
    {
        $user = User::query()->find(1);

        queue_push(new ModelJob($user));

        return 'success';
    }

    public function annotation()
    {
        di()->get(QueueService::class)->annotation([
            'group@hyperf.io',
            'https://doc.hyperf.io',
            'https://www.hyperf.io',
        ]);

        return 'success';
    }

    public function annotation2()
    {
        di()->get(QueueService::class)->annotationdelay([
            'group@hyperf.io',
            'https://doc.hyperf.io',
            'https://www.hyperf.io',
        ]);

        return 'success';
    }

    public function annotation3()
    {
        $user = User::query()->find(1);

        di()->get(QueueService::class)->annotationmodel($user);

        return 'success';
    }

    public function count()
    {
        queue_push(new CountJob());
        return 'success';
    }

    public function retry()
    {
        queue_push(new RetryJob());
        return 'success';
    }

    public function oneMinute()
    {
        di()->get(QueueService::class)->oneMinute();
        return 'success';
    }

    public function exception()
    {
        var_dump(AspectCollector::list());
        throw new BusinessException(ErrorCode::USER_DEFINED_ERROR);
    }
}